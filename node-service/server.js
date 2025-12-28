require('dotenv').config();
const express = require('express');
const mysql = require('mysql2');
const cors = require('cors');

const app = express();
const PORT = process.env.PORT || 3000;

app.use(cors());
app.use(express.json());

// Create connection pool
const pool = mysql.createPool({
    host: process.env.DB_HOST,
    user: process.env.DB_USERNAME,
    password: process.env.DB_PASSWORD,
    database: process.env.DB_DATABASE,
    waitForConnections: true,
    connectionLimit: 10,
    queueLimit: 0
});

// Helper for query execution
const db = pool.promise();

// Routes
app.get('/', (req, res) => {
    res.json({ message: 'Corelasi Node Service is running!' });
});

/**
 * GET /api/schedule
 * Fetch schedule for a specific teacher/user.
 * Query Params: ?user_id=X (Optional, defaults to fetching all for today for demo)
 */
app.get('/api/schedule', async (req, res) => {
    try {
        const userId = req.query.user_id; // Pass user_id explicitly since we don't share Auth session easily
        const today = new Date().toLocaleDateString('en-US', { weekday: 'long' }); // e.g. "Monday"

        // Map English day to Database integer (0=Mon, 6=Sun) or String if DB uses Strings
        // Looking at seeders, it seems DB uses Integers 0-6 or 1-7.
        // Let's assume standard Carbon DayOfWeek: 0=Sunday, 1=Monday... 
        // Wait, Laravel uses 0 for Sunday usually?
        // Let's check a sample query or just return all for specific teacher to start safe.
        // DashboardController: ->where('weekday', $today) where $today = Carbon::now()->dayOfWeek;
        // Carbon::dayOfWeek returns 0 (Sunday) to 6 (Saturday).

        const dayOfWeek = new Date().getDay(); // 0 = Sunday, 1 = Monday... matches Carbon.

        /* 
         * Query Logic:
         * Join schedule_sessions -> subjects
         * Join schedule_sessions -> classrooms
         * Join teachers -> users (to filter by user_id)
         */

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
