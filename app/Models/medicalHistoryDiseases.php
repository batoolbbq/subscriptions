<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class medicalHistoryDiseases extends Model
{
    use HasFactory;
    protected $guarded = [];   


    public function medical_history_profiles() {
        return $this->belongsTo(medicalHistoryProfile::class);
    }
   
    public function chronicdiseases() {
        return $this->belongsTo(Chronicdiseases::class);

    }
    
    public function drug_history_diseases() {
        return $this->hasMany(drugHistoryDiseases::class);
    }

}
