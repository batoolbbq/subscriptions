<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class patshNumber extends Model
{
    use HasFactory;
    protected $guarded = [];
    
    public function brand_name() {
        return $this->belongsTo(supplierBrandName::class , 'supplier_brand_name_id' , 'id');
    }

    public function BrandNameStock() {
        return $this->belongsTo(BrandNameStock::class);
    }
    
    public function supplier() {
        return $this->belongsTo(MedicalSupplier::class ,  'medical_suppliers_id', 'id' );
    }
    
    public function genaric_name() {
        return $this->belongsTo(genaricName::class , 'genaric_names_id' , 'id');
    }


}