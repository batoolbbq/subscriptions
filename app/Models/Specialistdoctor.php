<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specialistdoctor extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'practicecertificate','users_id',
       ];
    public $timestamps = false;
    public function users() {
        return $this->belongsTo(User::class);

    }
    public function userType() {
        return $this->belongsTo(UserType::class);

    }  public function medicalspecialties() {
        return $this->belongsTo(Medicalspecialty::class);

    }
    public function cities() {
        return $this->belongsTo(City::class);

    }
    public function assigns() {
        return $this->belongsTo(Assign::class);

    }
    public function prescriptions() {
        return $this->belongsTo(Prescription::class);
    }
    public function appointments() {
        return $this->belongsTo(Appointment::class);
    }
}

