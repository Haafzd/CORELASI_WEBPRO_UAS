<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeachingJournal extends Model
{
    protected $fillable = [
        'schedule_session_id',
        'journal_date',
        'topic',
        'observation_notes',
        'location'
    ];

    public function attendanceRecords()
    {
        return $this->hasMany(AttendanceRecord::class);
    }

    public function session()
    {
        return $this->belongsTo(ScheduleSession::class, 'schedule_session_id');
    }
}
