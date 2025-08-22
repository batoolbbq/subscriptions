<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrthopedicExamination extends Model
{
    use HasFactory, SoftDeletes;

    public function examination(): MorphOne
    {
        return $this->morphOne(Examination::class, 'examable');
    }
}
