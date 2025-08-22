<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{
    use HasFactory;
    
    protected $table = 'hospitals';
    
    public function hospitals(){
        return $this->hasMany(HospitalCategory::class, 'hospital_id', 'id');
    }
    
    public function hospital_specialties_services() {
        return $this->hasMany(HospitalSpecialtyService::class , 'hospital_id','id');
    }

    
    public function users(){
        return $this->belongsToMany(User::class, HospitalUser::class, 'hospital_id', 'user_id');
    }

    public function appointment()
    {
        return $this->morphOne('\App\Models\ServiceProviderAppointment', 'model');
    }
}
