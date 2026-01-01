require('dotenv').config();
const express = require('express');
const mysql = require('mysql2');
const cors = require('cors');

const app = express();
const PORT = process.env.PORT || 3000;

app.use(cors());
app.use(express.json());

// create connection pool
const pool = mysql.createPool({
    host: process.env.DB_HOST,
    user: process.env.DB_USERNAME,
    password: process.env.DB_PASSWORD,
    database: process.env.DB_DATABASE,
    waitForConnections: true,
    connectionLimit: 10,
    queueLimit: 0
});

// Helper buat ngejalanin query
const db = pool.promise();


app.get('/', (req, res) => {
    res.json({ message: 'Corelasi Node Service is running!' });
});


app.get('/api/schedule', async (req, res) => {
    try {
        const userId = req.query.user_id; 
        const today = new Date().toLocaleDateString('en-US', { weekday: 'long' });
        const dayOfWeek = new Date().getDay(); 
        let query = `
            SELECT 
                ss.id,
                ss.start_time,
                ss.end_time,
                ss.weekday,
                s.name as subject_name,
                s.code as subject_code,
                c.name as classroom_name,
                u.full_name as teacher_name
            FROM schedule_sessions ss
            JOIN subjects s ON ss.subject_code = s.code
            JOIN classrooms c ON ss.classroom_id = c.id
            JOIN teachers t ON ss.teacher_nip = t.nip
            JOIN users u ON t.user_id = u.id
            WHERE ss.weekday = ?
        `;

        const params = [dayOfWeek];

        if (userId) {
            query += ` AND u.id = ?`;
            params.push(userId);
        }

        query += ` ORDER BY ss.start_time ASC`;

        const [rows] = await db.query(query, params);

        res.json({
            status: 'success',
            day: dayOfWeek,
            total: rows.length,
            data: rows
        });

    } catch (error) {
        console.error(error);
        res.status(500).json({ status: 'error', message: error.message });
    }
});

app.listen(PORT, () => {
    console.log(`Server running on http://localhost:${PORT}`);
});
