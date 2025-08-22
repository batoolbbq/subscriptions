<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class drugHistoryDiseases extends Model
{
    
    use HasFactory;
    protected $guarded=[];

    public function medical_history_diseases() {
        return $this->belongsTo(medicalHistoryDiseases::class);
    }

    public function medical_history_profiles() {
        return $this->belongsTo(medicalHistoryProfile::class);
    }

    public function  genaric_names() {
        return $this->belongsTo(genaricName::class);
    }


}
