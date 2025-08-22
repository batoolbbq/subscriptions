<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicalprofile extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
     'product','customers_id','chronicdiseases_id','assigns','retireds_id','Diagnosis_date','prescription','medical_report','follow_card',
    ];
    // protected  $guarded = ['prescription']; 
    public function chronicdiseases() {
        return $this->belongsTo(Chronicdiseases::class);

    }
   
    public function retireds() {
        return $this->belongsTo(retired::class);

    }
    public function customers() {
        return $this->belongsTo(Customer::class);

    }
}
