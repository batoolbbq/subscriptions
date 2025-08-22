<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacilitySpecialistService extends Model
{
    use HasFactory;
    protected $table = 'facility_specialist_services';

    protected $fillable = [
        'facility_id',
        'specialties_services_id',
    ];
}
