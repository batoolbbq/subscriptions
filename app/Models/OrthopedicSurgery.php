<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class OrthopedicSurgery extends Model
{
    use HasFactory;
    public function surgery(): MorphOne
    {
        return $this->morphOne(Surgery::class, 'surgeryable');
    }
}
