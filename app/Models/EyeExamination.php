<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class EyeExamination extends Model
{
    use HasFactory;

    // public function eyeSurgery()
    // {
    //     return $this->hasOne(EyeSurgery::class);
    // }
    public function examination(): MorphOne
    {
        return $this->morphOne(Examination::class, 'examable');
    }
}
