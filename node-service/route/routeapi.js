const express = require('express');
const router = express.Router();
const { checkAccess, getAccessDoorID, OpenDoor,check_tokenid_member } =  require('./function.js');

router.get('/qa', async (req, res) => {
  try {
      const { cardid,cjihao,mjihao} = req.query;
      console.log(cardid);

      const rscheck_tokenid_member = await check_tokenid_member(cardid);
      console.log(rscheck_tokenid_member);
      if(rscheck_tokenid_member === false){
        const rscheckAccess = await checkAccess(cardid);
        if(rscheckAccess === false){
          return  res.status(200).json({
              success: false,
              message: 'Fields access for BU. ' + cardid,
              cardid: cardid
          }); 
        }  
      }


      // const rscheckAccess = await checkAccess(cardid);
      // if(rscheckAccess === false){
      //   return  res.status(200).json({
      //       success: false,
      //       message: 'Fields access for BU. ' + cardid,
      //       cardid: cardid
      //   }); 
      // }

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
        message: 'Successful open doorid ' + rsOpenDoor,
        cardid: cardid
      });

  } catch (error) {
    return  res.status(200).json({
        success: false,
        message: 'Fields.',
        cardid: 0
    });
  }  
});

module.exports = router;