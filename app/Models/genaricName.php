<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class genaricName extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function medical_history_diseases() {
        return $this->hasMany(drugHistoryDiseases::class);
    }

    public function drug_claims() {
        return $this->hasMany(drug_claim::class);
    }

    public function mid_claims() {
        return $this->hasMany(midClaim::class);
    }

    public function brand_names() {
        return $this->hasMany(BrandName::class);
    }
    public function patsh_numbers() {
        return $this->hasMany(patshNumber::class , 'genaric_names_id' , 'id');
    }

    public function chronicGrenaricName() {
        return $this->hasMany(chronicGrenaricName::class , 'genaric_names_id' , 'id');
    }

    public function supplier_brand_name() {
        return $this->hasMany(supplierBrandName::class);
    }

    public function supplier_brand_name2() {
        return $this->hasMany(supplierBrandName2::class);
    }

    public function supplier_active_substances() {
        return $this->hasMany(supplierActiveSubstance::class);
    }
    
    public function BrandNameStock() {
        return $this->hasMany(BrandNameStock::class  , 'genaric_names_id' , 'id');
    }
}
