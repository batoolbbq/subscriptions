<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class activeSubstanceUnit extends Model
{
    use HasFactory;
    protected $table ="active_substance_unit";
    protected $guarded=[];

    public function active_substance_copies() {
        return $this->belongsTo(ActiveSubstanceCopy::class);

    }

    public function unit() {
        return $this->belongsTo(unit::class);

    }

}
