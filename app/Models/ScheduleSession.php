<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduleSession extends Model
{
    protected $fillable = [
        'semester_id','classroom_id','subject_code','teacher_nip',
        'weekday','specific_date','start_time','end_time','is_active','remark'
    ];

    public function subject() { return $this->belongsTo(Subject::class, 'subject_code', 'code'); }
    public function classroom() { return $this->belongsTo(Classroom::class); }
    public function teacher() { return $this->belongsTo(Teacher::class, 'teacher_nip', 'nip'); }
}
