<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\HospitalService;

class HospitalServicesCategory extends Model
{
    use HasFactory;
    
    protected $table = 'hospital_services_categories';
    
    public function services(){
        return $this->hasMany(HospitalService::class, 'hospital_services_categories_id', 'id');
    }
}
