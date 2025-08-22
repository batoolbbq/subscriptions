<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerAppointment extends Model
{
    use HasFactory;

    public function customer(){
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }

    public function appointment(){
        return $this->hasOne('\App\Models\ServiceProviderAppointment', 'id', 'service_provider_appointment_id');
    }
}
