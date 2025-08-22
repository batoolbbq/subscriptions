<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    use HasFactory;

    protected $table = 'referrals';
    protected $guarded = [];


    public function medicalexamination()
    {
        return $this->belongsTo(medicalexamination::class, 'medicalexamination_id');
    }
    public function specialty()
    {
        return $this->belongsTo(specialtiesServices::class, 'specialties_services_id');
    }
}
