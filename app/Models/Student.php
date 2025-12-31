<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model
{
    protected $primaryKey = 'nis';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['nis','user_id','classroom_id','entry_cohort','short_bio'];

    public function user(): BelongsTo { return $this->belongsTo(User::class,'user_id','id'); }
    public function classroom(): BelongsTo { return $this->belongsTo(Classroom::class); }
}
