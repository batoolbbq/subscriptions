<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class medicalHistoryProfile extends Model
{
    use HasFactory;
    protected $guarded = [];   

    
    public function medical_history_diseases() {
        return $this->hasMany(medicalHistoryDiseases::class);
    }

    public function drug_history_diseases() {
        return $this->hasMany(drugHistoryDiseases::class);
    }

    public function customers() {
        return $this->belongsTo(Customer::class);
    }
    
    public function user() {
        return $this->belongsTo(User::class);
    }
    // public function medical_history_diseases() {
    //     return $this->hasMany(medicalHistoryProfile::class);
    // }
    

}
