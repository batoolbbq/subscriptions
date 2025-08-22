<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tumorsDignoses extends Model
{
    use HasFactory;
    protected $guarded = [];


    public function oncologyDignoses()
    {
        return $this->hasOne(oncologyDignoses::class, 'id', 'oncology_dignoses_id');
    }

}
