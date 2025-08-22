<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class servise_request extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function medical_services()
    {
        return $this->belongsTo(medicalService::class , 'medical_services_id' , 'id');

    }


}
