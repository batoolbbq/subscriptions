<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrandName extends Model
{
    use HasFactory;
    protected $guarded=[];
    
    public function active_substance_copies() {
        return $this->belongsTo(ActiveSubstanceCopy::class);

    }

    public function phasupplier_brand_names() {
        return $this->hasMany(supplierBrandName::class);
    }
}
