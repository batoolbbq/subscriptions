<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sysChronicDisease extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function chronicdiseases() {
        return $this->belongsTo(Chronicdiseases::class);

    }

    public function medical_claim_profiles() {
        return $this->belongsTo(medicalClaimProfile::class);
    }

}
