<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\HospitalSubService;

class HospitalService extends Model
{
    use HasFactory;
    
    protected $table = 'hospital_services';
    
    public function subservices(){
        return $this->hasMany(HospitalSubService::class, 'hospital_services_id', 'id');
    }
    
    public function category(){
        return $this->hasOne(HospitalServicesCategory::class, 'id', 'hospital_services_categories_id');
    }
}
