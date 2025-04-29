const mysql = require('mysql2/promise');
require('dotenv').config();
const config = process.env;

const pool = mysql.createPool({
  host: config.HOST_DB, 
  user: config.USER_DB,
  password: config.PASSWORD_DB,
  database: config.DATABASE_NAME,
  port: config.PORT_DB,
});

module.exports = pool;
