<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class orderBrandNameStock extends Model
{
    use HasFactory;

    public function supplier_brand_name() {
        return $this->belongsTo(supplierBrandName::class);
    }

    public function patsh_numbers() {
        return $this->belongsTo(patshNumber::class);
    }

   public function supplier() {
        return $this->belongsTo(MedicalSupplier::class ,  'supplier_id', 'id' );
    }
}
