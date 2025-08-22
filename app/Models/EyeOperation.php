<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class EyeOperation extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function surgery(): MorphOne
    {
        return $this->morphOne(Surgery::class, 'surgeryable');
    }
}
