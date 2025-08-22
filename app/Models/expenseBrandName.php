<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class expenseBrandName extends Model
{
    use HasFactory;
    
        protected $guarded = [];
        
        public function supplier_brand_name() {
            return $this->belongsTo(supplierBrandName::class);
        }

        public function supplier_brand_name2() {
            return $this->belongsTo(supplierBrandName2::class , 'supplier_brand_name_id', 'id');
        }

        public function mid_claims() {
            return $this->belongsTo(midClaim::class);
        }

        public function pharmacy() {
            return $this->belongsTo(Pharmacy::class ,  'pharmacies_id', 'id' );
        }
        
        public function genaric_name() {
            return $this->belongsTo(genaricName::class , 'genaric_names_id' , 'id');
        }
}
