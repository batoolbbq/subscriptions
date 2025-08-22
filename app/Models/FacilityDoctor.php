<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacilityDoctor extends Model
{
    use HasFactory;
        protected $guarded = [];
        
    public function facility()
    {
        return $this->belongsTo(Facility::class , 'facility_id' , 'id');
    }
    public function User()
    {
        return $this->belongsTo(User::class , 'user_id' , 'id');
    }

}
