<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Generalpractitioner extends Model
{
    use HasFactory;

    protected $fillable = [
        'practicecertificate', 'users_id',
    ];
    public $timestamps = false;
    public function users()
    {
        return $this->belongsTo(User::class);
    }
    public function userType()
    {
        return $this->belongsTo(UserType::class);
    }
    public function assigns()
    {
        return $this->belongsTo(Assign::class);
    }
    public function prescriptions()
    {
        return $this->belongsTo(Prescription::class);
    }
    public function claims()
    {
        return $this->belongsTo(Claim::class);
    }
}
