const express = require('express');
const app = express();
const cors = require('cors');
const cookieParser = require('cookie-parser'); 

require('dotenv').config();
const config = process.env;

const corsOptions = {  
  // origin: (origin, callback) => {
  //   const allowedOrigins = config.ALLOWED_ORIGINS.split(',');
  //   if (allowedOrigins.indexOf(origin) !== -1 || !origin) {
  //     // Commands that allow Origins or no origins (Such as using postman)      
  //     callback(null, true);
  //   } else {
  //     // If the domain is not authorized
  //     callback(new Error('Not allowed by CORS'));
  //   }
  // },
  origin: '*',
  methods: ['GET', 'POST', 'PUT', 'DELETE','PATCH'],
  allowedHeaders: ['Content-Type', 'Authorization']
};

app.use(express.json());
app.use(express.urlencoded({ extended: true }));
app.use(cookieParser());
app.use(cors(corsOptions));

const route = require('./route/routeapi.js');
app.use('/v1/api', route);

app.get('/', (req, res) => {
  return res.status(200).json({ message: 'Path not found!' });
});

app.listen(config.PORT, () => {
  console.log(`Server running at PORT : ${config.PORT}`);
});