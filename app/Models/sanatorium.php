<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sanatorium extends Model
{
    use HasFactory;
        protected $guarded =[];

    public function sanatoria_id() {
        return $this->hasMany(sanatoiumServ::class, 'sanatorium_id', 'id');
    }


    
    public function appointment()
    {
        return $this->morphOne('\App\Models\ServiceProviderAppointment', 'model');
    }

}
