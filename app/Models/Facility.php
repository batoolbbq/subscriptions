<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\BelongsToRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Facility extends Model
{
    use HasFactory;


    public function specialtiesServices(): BelongsToMany
    {
        return $this->belongsToMany(specialtiesServices::class, 'facility_specialist_services', 'facility_id', 'specialties_services_id');
    }
    public function doctors(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'facility_doctors', 'facility_id', 'user_id');
    }
}
