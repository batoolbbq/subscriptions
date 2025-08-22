<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HospitalCategory extends Model
{
    use HasFactory;
    
    protected $table = 'hospital_categories';
    
    protected $guarded = [];
   
//    public function subservices(){
//        return $this->belongsTo(HospitalSubService::class, 'id', 'hospital_sub_services_id');
//    }


    public function hospital_sub_services_id(){
        return $this->belongsTo(HospitalSubService::class, 'hospital_sub_services_id', 'id');
    }
   
}
