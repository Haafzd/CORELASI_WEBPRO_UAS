<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Teacher extends Model
{
    protected $primaryKey = 'nip';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['nip','user_id','phone','is_duty_teacher'];

    public function user(): BelongsTo { return $this->belongsTo(User::class,'user_id','id'); }
}
