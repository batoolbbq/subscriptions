<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HospitalSpecialtyService extends Model
{
    use HasFactory;
    
    protected $guarded = [];
    
    protected $table = 'hospital_specialties_services';
    
    public function specialties_services_id() {
    
        return $this->belongsTo(specialtiesServices::class , 'specialties_services_id' , 'id');
    
    }

    
}
