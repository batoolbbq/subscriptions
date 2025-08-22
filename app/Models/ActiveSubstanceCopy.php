<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActiveSubstanceCopy extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function medical_history_diseases() {
        return $this->hasMany(drugHistoryDiseases::class);
    }

    public function drug_claims() {
        return $this->hasMany(drug_claim::class);
    }

    public function mid_claims() {
        return $this->hasMany(midClaim::class);
    }


    public function active_substance_unit() {
        return $this->hasMany(activeSubstanceUnit::class);
    }

    public function brand_names() {
        return $this->hasMany(BrandName::class);
    }

    public function supplier_active_substances() {
        return $this->hasMany(supplierActiveSubstance::class);
    }
    


}
