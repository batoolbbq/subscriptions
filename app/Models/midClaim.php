<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class midClaim extends Model
{
    use HasFactory;
    protected $guarded=[];

    //     public function active_substance_copies() {
    //     return $this->belongsTo(ActiveSubstanceCopy::class);
    // }

        public function medicalexaminations()
    {
        return $this->belongsTo(medicalexamination::class);
    }
        public function genaric_names()
    {
        return $this->belongsTo(genaricName::class);
    }

    //     public function genaric_names2()
    // {
    //     return $this->hasManyThrough( genaricName::class , supplierBrandName::class , '' , '' );
    // }
}
