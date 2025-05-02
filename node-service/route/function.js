const axios = require('axios');
require('dotenv').config();
const config = process.env;
const db = require('../db/mysql.js');
const e = require('express');

const check_tokenid_member = async (tokenid) => {
    let connection;
    try {              
        connection = await db.getConnection();     
        tokenid = String(tokenid);
        //เดี๋ยวตัว enddate ทำเพิ่ม
        const [rs] = await db.query(`SELECT * FROM member WHERE cardnumber = ? and active = ? and status = ?`, [tokenid,1,1]); 
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

module.exports = { checkAccess, getAccessDoorID, OpenDoor, check_tokenid_member }