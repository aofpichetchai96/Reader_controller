const express = require('express');
const router = express.Router();
const { checkAccess, getAccessDoorID, OpenDoor,check_tokenid_member } =  require('./function.js');

// let cnt_rs = 0;
router.get('/qa', async (req, res) => {
  try {
      const { cardid,cjihao,mjihao} = req.query;
      // console.log(cardid);
      let rscheckAccess;

      //เช็คว Token ID ว่าเป็น Staft หรือไม่
      const rscheck_tokenid_member = await check_tokenid_member(cardid);
      // console.log('rscheck_tokenid_member : ',rscheck_tokenid_member);
      if(rscheck_tokenid_member === false){//กรณีไม่ใช่ Staft 
        
        //เช็คกับทาง BU ว่าอณุญาติหรือไม่
        rscheckAccess = await checkAccess(cardid);
        // console.log('rscheckAccess : ',rscheckAccess);
        if(rscheckAccess.success === false){
          return  res.status(200).json({
              success: rscheckAccess.success,
              message: rscheckAccess.message,
              cardid: cardid
          }); 
        }  

        const rsDoorId = await getAccessDoorID(cjihao,mjihao);
        if(rsDoorId === false){
          return  res.status(200).json({
              success: false,
              message: rscheckAccess.message+', Fields access door. ' + cjihao,
              cardid: cardid
          }); 
        }

        const rsOpenDoor = await OpenDoor(rsDoorId);    
        if(rsOpenDoor === false){
          return  res.status(200).json({
              success: false,
              message: rscheckAccess.message+', Fields OpenDoor. ' + rsDoorId,
              cardid: cardid
          }); 
        }
        // cnt_rs = cnt_rs + 1;
        // console.log('cnt_rs : ',cnt_rs);
        return res.status(200).json({
          success: true,
          message: rscheckAccess.message,
          cardid: cardid
        });

      }else{
        //Staft
        const rsDoorId = await getAccessDoorID(cjihao,mjihao);
        if(rsDoorId === false){
          return  res.status(200).json({
              success: false,
              message: 'Fields access door. ' + cjihao,
              cardid: cardid
          }); 
        }
  
        const rsOpenDoor = await OpenDoor(rsDoorId);    
        if(rsOpenDoor === false){
          return  res.status(200).json({
              success: false,
              message: 'Fields OpenDoor. ' + rsDoorId,
              cardid: cardid
          }); 
        }
        
        return res.status(200).json({
          success: true,
          message: 'Successful Statf.',
          cardid: cardid
        });
      }
  } catch (error) {
    return  res.status(200).json({
        success: false,
        message: 'Fields service node.',
        cardid: 0
    });
  }  
});
  
module.exports = router;