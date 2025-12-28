<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    protected $fillable = [
        'assignment_id','student_nis','file_path','original_name','student_note','submitted_at',
        'status','score','feedback'
    ];
}
