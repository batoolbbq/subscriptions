<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalTests extends Model
{
    use HasFactory;

    public function testsRequest()
    {
        return $this->belongsTo(MedicalTestsRequest::class, 'test_request_id');
    }

    public function testsParameters()
    {
        return $this->hasMany(MedicalTestsParameters::class, 'test_id', 'id');
    }
}