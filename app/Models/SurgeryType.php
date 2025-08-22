<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurgeryType extends Model
{
    use HasFactory;

    public function subtypes()
    {
        return $this->hasMany(SurgerySubtype::class);
    }
}
