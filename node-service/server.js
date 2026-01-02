require("dotenv").config();
const express = require("express");
const mysql = require("mysql2");
const cors = require("cors");

const app = express();
const PORT = process.env.PORT || 3000;

app.use(cors());
app.use(express.json());

const pool = mysql.createPool({
    host: process.env.DB_HOST,
    user: process.env.DB_USERNAME,
    password: process.env.DB_PASSWORD,
    database: process.env.DB_DATABASE,
    waitForConnections: true,
    connectionLimit: 10,
    queueLimit: 0,
});

const db = pool.promise();

app.get("/", (req, res) => {
    res.json({ message: "Corelasi Node Service is running!" });
});

app.get("/api/schedule", async (req, res) => {
    try {
        const userId = req.query.user_id;

        const daysOfWeeks = [
            "Minggu",
            "Senin",
            "Selasa",
            "Rabu",
            "Kamis",
            "Jumat",
            "Sabtu",
        ];
        const todayIndex = new Date().getDay();
        const currentDayName = daysOfWeeks[todayIndex];

        let query = `
            SELECT 
                ss.id,
                ss.start_time,
                ss.end_time,
                ss.weekday,
                s.name as subject_name,
                s.code as subject_code,
                s.description as subject_description,
                c.name as classroom_name,
                u.full_name as teacher_name,
                t.nip as teacher_nip
            FROM schedule_sessions ss
            JOIN subjects s ON ss.subject_code = s.code
            JOIN classrooms c ON ss.classroom_id = c.id
            JOIN teachers t ON ss.teacher_nip = t.nip
            JOIN users u ON t.user_id = u.id
        `;

        const params = [];
        const conditions = [];

        conditions.push("ss.is_active = 1");

        if (userId) {
            conditions.push("u.id = ?");
            params.push(userId);
        } else {
            conditions.push("ss.weekday = ?");
            params.push(currentDayName);
        }

        if (conditions.length > 0) {
            query += " WHERE " + conditions.join(" AND ");
        }

        query += ` ORDER BY ss.start_time ASC`;

        const [rows] = await db.query(query, params);

        res.json({
            status: "success",
            filter_type: userId ? "user_all" : "today_global",
            day_request: currentDayName,
            total: rows.length,
            data: rows,
        });
    } catch (error) {
        console.error(error);
        res.status(500).json({ status: "error", message: error.message });
    }
});

app.listen(PORT, () => {
    console.log(`Server running on http://localhost:${PORT}`);
});
