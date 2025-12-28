require('dotenv').config();
const mysql = require('mysql2');

const connection = mysql.createConnection({
    host: process.env.DB_HOST,
    user: process.env.DB_USERNAME,
    password: process.env.DB_PASSWORD,
    database: process.env.DB_DATABASE
});

const query = "ALTER TABLE learning_materials MODIFY external_link VARCHAR(2048) NULL";

connection.query(query, (err, results) => {
    if (err) {
        console.error('Error fixing DB:', err);
    } else {
        console.log('Successfully made external_link nullable.');
    }
    connection.end();
});
