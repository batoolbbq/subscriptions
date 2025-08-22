<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EyeSurgery extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function surgery()
    {
        return $this->belongsTo(Surgery::class);
    }

    public function followUpVisits()
    {
        return $this->hasMany(FollowUpVisit::class);
    }
}
