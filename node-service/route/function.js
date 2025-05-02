const axios = require('axios');
require('dotenv').config();
const config = process.env;
const db = require('../db/mysql.js');
const cron = require('node-cron');

function checkEndDateValid(data) {
    const endDate = data.enddate instanceof Date ? data.enddate : new Date(data.enddate);    
    const currentDate = new Date();
    if (currentDate > endDate) {
      return false;
    }    
    return true;
  }
const check_tokenid_member = async (tokenid) => {
    let connection;
    try {              
        connection = await db.getConnection();     
        tokenid = String(tokenid);
     
        const [rs] = await db.query(`SELECT * FROM member WHERE cardnumber = ? and active = ? and status = ?`, [tokenid,1,1]); 
      
        const ck_endate = checkEndDateValid(rs[0]);
        if(!ck_endate) return false;

        if(rs.length === 0) return false;        
        console.log('Successful Member Token : ', tokenid);
        return rs[0].id;     
    } catch (error) {
        return false;
    } finally {
        if (connection) connection.release(); 
    }  
}


const checkAccess = async (tokenid) => {    
    let message_rs = '';
    try {
        // console.log(tokenid.length);
        if(tokenid.length > config.LENGTH_SERIAL_CARD){
            const data = {
                "FromSystem": config.FROMSYSTEM_HEYDAY,
                "SecureCode": config.SECURECODE_HEYDAY,
                "StringCode": tokenid
            }     
            const headers = {'Content-Type': 'application/json'};
            const rs = await axios.post(config.URL_VALIDQRCODE, data ,{ headers: headers });          
            if(rs.data.statusValid === 'true'){
                console.log('Successful QRCode Token : ', tokenid);
                message_rs = rs.data.message;
                let data_rs = {
                    success: true,
                    message: message_rs
                }            
                return data_rs;
            }
            else{     
                message_rs = rs.data.message;
                console.log('Fields QRCode Token : ', tokenid);       
                let data_rs = {
                    success: false,
                    message: message_rs,
                }            
                return data_rs;
            } 
        }else{                
            const data_card = {
                "StringCode" : tokenid,
                "FromSystem" : config.FROMSYSTEM_HEYDAY,
                "SecureCode" : config.SECURECODE_HEYDAY_CARD
            }     
            const headers = {'Content-Type': 'application/json'};
            const rs_card = await axios.post(config.URL_VALIDCARD, data_card ,{ headers: headers });

            if(rs_card.data.statusValid === 'true'){
                console.log('Successful Card Token : ', tokenid);
                message_rs = rs_card.data.message;
                let data_rs = {
                    success: true,
                    message: message_rs
                }            
                return data_rs;
            }else{               
                console.log('Fields Card Token : ', tokenid);   
                message_rs = rs_card.data.message;    
                let data_rs = {
                    success: false,
                    message: message_rs,
                }            
                return data_rs; 
            }        
        }
    }catch (error) {
        console.log('Error in checkAccess : ', error.message);
        let data_rs = {
            success: false,
            message: error.message,
        }            
        return data_rs;
    } 
}

const getAccessDoorID = async (cjihao,mjihao) => {
    let connection;
    try {         
        connection = await db.getConnection();     
        const [rs] = await db.query(`SELECT * FROM asc_list_door WHERE cjihao = ? AND mjihao = ?`, [cjihao,mjihao]);
        if(rs.length === 0) return false;        
        return rs[0].id;     
    } catch (error) {
        return false;
    } finally {
        if (connection) connection.release(); 
    }  
}

const OpenDoor = async (doorid) => {
    try {    
        await new Promise(resolve => setTimeout(resolve, 300));       
        await axios.get(`${config.URI_ACCESS_DOOR}?door=${doorid}`)
        console.log('open doorid : ', doorid);
        await new Promise(resolve => setTimeout(resolve, 250));
        return true;   
    } catch (error) {
        return false;   
    }  
}

// cron.schedule('0 * * * *', async() => { // 1 ชั่วโมง
// cron.schedule('* * * * *', async () => { //1 นาที
cron.schedule('0 1 * * *', async () => { //รันทุกวัน เวลา 01:00:00
    try {        
        const response = await schedule_clear_log_30_days();
        console.log('schedule_clear_log_30_days :', response);
    } catch (error) {
        console.error('schedule_clear_log_30_days Error:', error.message);
    }
});

const formatDate = (date) => {
    const pad = (n) => n.toString().padStart(2, '0');
    return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())} ` +
           `${pad(date.getHours())}:${pad(date.getMinutes())}:${pad(date.getSeconds())}`;
};  

const schedule_clear_log_30_days = async () => {
    let message_logs = '';
    let count_log = 0;
    let starttime;
    let endtime;
    const now = new Date();
    starttime = formatDate(now);
    const pastDate = new Date(now.setDate(now.getDate() - 30));
    const formattedPastDate = formatDate(pastDate);

    try{                    
        const cnt_log = await db.query(`SELECT COUNT(id) as cnt FROM scan_logs WHERE createtime < ?`, [formattedPastDate]);
        if(cnt_log[0][0].cnt === 0){
            message_logs  = `No scan_logs. Count : ${cnt_log[0][0].cnt}`;    
            count_log = 0;
            const endtime_now = new Date();
            endtime = formatDate(endtime_now);    
            const sql_insert = `INSERT INTO clear_logs (count_log, message, datetime_cls, starttime, endtime) VALUES (?, ?, ?, ?, ?)`;
            const values = [count_log, message_logs, formattedPastDate, starttime, endtime];
            await db.query(sql_insert, values);
            return true;
        }
        else{
            message_logs  = `Count scan_logs : ${cnt_log[0][0].cnt}, `;
            count_log = cnt_log[0][0].cnt;
            const rs_delete = await db.query(`DELETE FROM scan_logs WHERE createtime < ?`, [formattedPastDate]);
            if(rs_delete[0].affectedRows > 0){
                message_logs += ` Delete scan_logs successfully.`; 
            }else{
                message_logs += ` Delete scan_logs failed.`; 
            }

            const endtime_now = new Date();
            endtime = formatDate(endtime_now);
            const sql_insert = `INSERT INTO clear_logs (count_log, message, datetime_cls, starttime, endtime) VALUES (?, ?, ?, ?, ?)`;
            const values = [count_log, message_logs, formattedPastDate, starttime, endtime];
            await db.query(sql_insert, values);
            return true;
        }
    }catch(err){
        message_logs  = `Error in schedule_clear_log_30_days : ${err.message}`;
        count_log = 0; 
        const endtime_now = new Date();
        endtime = formatDate(endtime_now);
        const sql_insert = `INSERT INTO clear_logs (count_log, message, datetime_cls, starttime, endtime) VALUES (?, ?, ?, ?, ?)`;
        const values = [count_log, message_logs, formattedPastDate, starttime, endtime];
        await db.query(sql_insert, values);
        return true;
    }
} 

   
module.exports = { checkAccess, getAccessDoorID, OpenDoor, check_tokenid_member }