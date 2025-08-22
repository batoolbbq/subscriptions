<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicalspecialty extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
     'name',
    ];
    public function specialistdoctors() {
        return $this->hasMany(Specialistdoctor::class);
    }
    public function assigns() {
        return $this->hasMany(Assign::class);
    }
}
