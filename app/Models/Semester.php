<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Semester extends Model
{
    protected $fillable = ['academic_year_id','name','start_date','end_date','is_active'];

    public function year(): BelongsTo { return $this->belongsTo(AcademicYear::class,'academic_year_id'); }
}
