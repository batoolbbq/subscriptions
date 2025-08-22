<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

 class supplierBrandName extends Model
{
    use HasFactory;
    protected $guarded=[];


    public function brand_name() {
        return $this->belongsTo(BrandName::class);
    }

    public function genaric_name() {
        return $this->belongsTo(genaricName::class);
    }

    public function supplier() {
        return $this->belongsTo(MedicalSupplier::class ,  'supplier_id', 'id' );
    }

    public function patsh_numbers() {
        return $this->hasMany(patshNumber::class);
    }
}
