<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalTestsParameters extends Model
{
    use HasFactory;

    public function tests()
    {
       return $this->belongsTo(MedicalTests::class);
    }
}