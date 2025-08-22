<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sanatoiumServ extends Model
{
    use HasFactory;
    protected $guarded =[];

    public function specialties_services()
    {
        return $this->belongsTo(specialtiesServices::class, 'specialties_services_id', 'id');
    }

}
