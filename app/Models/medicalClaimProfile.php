<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class medicalClaimProfile extends Model
{
    use HasFactory;

    public function drug_claims() {
        return $this->hasMany(drug_claim::class);
    }

    public function sys_chronic_diseases() {
        return $this->hasMany(sysChronicDisease::class);
    }

    public function Generalpractitioner() {
        return $this->hasMany(Generalpractitioner::class);
    }
}
