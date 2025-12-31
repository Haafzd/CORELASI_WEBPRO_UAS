<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LearningMaterial extends Model
{
    protected $fillable = [
        'schedule_session_id','title','description','external_link','publish_status'
    ];

    public function session() { return $this->belongsTo(ScheduleSession::class, 'schedule_session_id'); }
}
