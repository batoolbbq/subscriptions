<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserType extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
    ];
    public function users() {
        return $this->hasMany(User::class);
    }
    public function specialistdoctors() {
        return $this->hasMany(Specialistdoctor::class);
    }
    public function generalpractitioners() {
        return $this->hasMany(Generalpractitioner::class);
    }
}
