<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceRecord extends Model
{
    protected $fillable = [
        'teaching_journal_id',
        'student_nis',
        'status',
        'notes'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_nis', 'nis');
    }
}
