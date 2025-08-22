<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class drug_claim extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function medical_claim_profiles() {
        return $this->belongsTo(medicalClaimProfile::class);
    }

    // public function active_substance_copies() {
    //     return $this->belongsTo(ActiveSubstanceCopy::class);
    // }

    
    public function  genaric_names() {
        return $this->belongsTo(genaricName::class);
    }


}
