<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subject extends Model
{
    use HasFactory;

    // Primary key menggunakan 'code'
    protected $primaryKey = 'code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['code', 'name', 'description', 'status'];

    // Route Model Binding berdasarkan 'code'
    public function getRouteKeyName()
    {
        return 'code';
    }

    // Relasi ke materi
    public function materials()
    {
        return $this->hasMany(Material::class, 'subject_code', 'code');
    }
}
