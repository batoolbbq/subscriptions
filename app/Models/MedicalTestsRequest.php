<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalTestsRequest extends Model
{
    use HasFactory;

    public function facility()
    {
        return $this->morphTo();
    }
    
    public function tests()
    {
        return $this->hasMany(MedicalTests::class, 'test_request_id', 'id');
    }

}