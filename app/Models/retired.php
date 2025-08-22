<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class retired extends Model
{
    use HasFactory; use SoftDeletes;
    public $timestamps = false;

    public function customers()
    {
        return $this->belongsTo(Customer::class);
    }
    public function personalphotos()
    {
        return $this->belongsTo(Personalphotos::class);
    }

    public function warrantyoffices()
    {
        return $this->belongsTo(Warrantyoffice::class);
    }
    public function healthfacilities()
    {
        return $this->belongsTo(Healthfacilities::class);
    }
    public function guarantybranches()
    {
        return $this->belongsTo(guarantybranch::class);
    }
    public function medicalprofiles()
    {
        return $this->hasMany(Medicalprofile::class);
    }
    public function assigns()
    {
        return $this->hasMany(Assign::class);
    }
    public function prescriptions()
    {
        return $this->belongsTo(Prescription::class);
    }
    public function appointments()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function managementappointments()
    {
        return $this->belongsTo(Managementappointment::class);
    }

    public function claims()
    {
        return $this->hasMany(Claim::class);
    }
}
