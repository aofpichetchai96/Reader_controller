const express = require('express');
const app = express();
const axios = require('axios');
const PORT = 850;

app.get('/qa/mcardsea', async (req, res) => {
  const { cardid } = req.query;

  if(Number(cardid) === 1325788163 ||  Number(cardid) === 1325634291 ||  Number(cardid) === 142536){
    console.log('successful cardid : ', cardid);

    const rs = await axios.get('http://localhost:1880/open?door=1');
    console.log('rs : ', rs.data);
    

    return  res.status(200).json({
        success: true,
        message: 'successful',
        cardid: cardid,
      }      
    )

  }
  else{
    console.log('fields cardid : ', cardid);
    return  res.status(200).json({
        success: false,
        message: 'fields',
        cardid: cardid,
      }      
    )
  }
});

app.listen(PORT, () => {
  console.log(`🚀 Server running at http://localhost:${PORT}`);
});