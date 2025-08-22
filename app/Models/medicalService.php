<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class medicalService extends Model
{
    use HasFactory;


    public function SpecializedServices()
    {
        return $this->hasMany(SpecializedServices::class , 'medical_services_id' , 'id');

    }
    public function package_medical_services()
    {
        return $this->hasMany(packageMedicalService::class , 'medical_services_id' , 'id');

    }


    public function children()
    {
        return $this->hasMany(MedicalService::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(MedicalService::class, 'parent_id');
    }
}
