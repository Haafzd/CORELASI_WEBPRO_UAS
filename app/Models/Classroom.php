<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Classroom extends Model
{
    protected $fillable = ['name','cohort_year','major','homeroom_teacher_nip'];

    public function homeroom(): BelongsTo { return $this->belongsTo(Teacher::class,'homeroom_teacher_nip','nip'); }

    public function students() { return $this->hasMany(Student::class); }
}
