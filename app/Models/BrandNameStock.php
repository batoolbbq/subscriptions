<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrandNameStock extends Model
{
    use HasFactory;


    public function supplier_brand_name() {
        return $this->belongsTo(supplierBrandName::class);
    }

    public function patsh_numbers() {
        return $this->belongsTo(patshNumber::class);
    }

   public function supplier() {
        return $this->belongsTo(MedicalSupplier::class ,  'medical_suppliers_id', 'id' );
    }

    
    public function pharmacy() {
        return $this->belongsTo(Pharmacy::class ,  'pharmacies_id', 'id' );
    }

    public function pharmacy_doctors_id() {
        return $this->belongsTo(PharmacyUser::class ,  'pharmacy_doctors_id', 'id' );
    }
    
    public function brand_name() {
        return $this->belongsTo(supplierBrandName::class , 'supplier_brand_name_id' , 'id');
    }


}
