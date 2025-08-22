<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EyeSurgeryRequest extends Model
{
    use HasFactory;

    public function eyeExamination()
    {
        return $this->belongsTo(EyeExamination::class);
    }
}
