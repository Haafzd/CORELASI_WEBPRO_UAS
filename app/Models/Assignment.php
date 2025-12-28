<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $fillable = [
        'schedule_session_id','title','instruction','external_problem_link','deadline_at','publish_status'
    ];

    protected $casts = ['deadline_at' => 'datetime'];

    public function session() { return $this->belongsTo(ScheduleSession::class, 'schedule_session_id'); }
    public function submissions() { return $this->hasMany(Submission::class); }
}
