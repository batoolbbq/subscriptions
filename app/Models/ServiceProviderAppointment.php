<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceProviderAppointment extends Model
{
    use HasFactory;

    protected $table = 'service_provider_appointments';

    public function model()
    {
        return $this->morphTo();
    }

    public function customers(){
        return $this->hasMany(CustomerAppointment::class, 'service_provider_appointment_id', 'id');
    }
}
