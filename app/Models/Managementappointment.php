<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Managementappointment extends Model
{
    use HasFactory;

    public $timestamps = false;


    public function customers()
    {
        return $this->belongsTo(Customer::class);
    }



    public function retireds()
    {
        return $this->belongsTo(retired::class);
    }
}
