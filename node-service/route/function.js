const axios = require('axios');
require('dotenv').config();
const config = process.env;
const db = require('../db/mysql.js');

const checkAccess = async (tokenid) => {    
    try {
        //เช็ค tokenid ที่อ่านได้ กับทางอาจารย์
        if(tokenid == 1325788163 ||  tokenid == 1325634291 ||  tokenid == 142536 || tokenid == `agsidohpadpahsbdhv;aodhv;osiHFp'odh'obha'pdhgopa`){    
            console.log('successful tokenid : ', tokenid);
            return true;
          }
          else{
            console.log('fields tokenid : ', tokenid);
            return false;
          }
    }catch (error) {
        // console.log('Error in checkAccess: ', error);
        return false;
    } 
}

const getAccessDoorID = async (cjihao,mjihao) => {
    try {                
        const [rs] = await db.query(`SELECT * FROM asc_list_door WHERE cjihao = ? AND mjihao = ?`, [cjihao,mjihao]);
        if(rs.length === 0) return false;        
        return rs[0].id;     
    } catch (error) {
        // console.log('Error in getAccessDoorID: ', error);
        return false;
    }  
}

const OpenDoor = async (doorid) => {
    try {           
        await axios.get(`${config.URI_ACCESS_DOOR}?door=${doorid}`)
        return true;   
    } catch (error) {
        return false;   
    }  
}

module.exports = { checkAccess, getAccessDoorID, OpenDoor }