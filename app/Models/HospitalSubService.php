<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HospitalSubService extends Model
{
    use HasFactory;
    
    protected $table = 'hospital_sub_services';
    
    protected $guarded = [];
    
    public function service(){
       return $this->hasOne(HospitalService::class, 'id', 'hospital_services_id');
    }
    
    
}
