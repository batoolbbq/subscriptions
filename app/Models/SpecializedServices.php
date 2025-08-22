<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecializedServices extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function specialtiesServices()
    {
        return $this->belongsTo(specialtiesServices::class , 'specialties_services_id', 'id');

    }


}
