<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class medicalexamination extends Model
{
    use HasFactory;
    protected $guarded=[];


        public function systemic_examination_claims() {
        return $this->hasOne(systemicExaminationClaim::class);
    }

        public function mid_claims() {
        return $this->hasMany(midClaim::class);
    }
    public function customer(){
        return $this->hasOne(Customer::class, 'id', 'customers_id');
    }
    
    public function user(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    
    public function surgery()
    {
        return $this->hasOne(Surgery::class);
    }
    public function examination()
    {
        return $this->belongsTo(Examination::class, 'id', 'medicalexamination_id');
    }
    public function tumorsDignoses() {
        return $this->hasMany(tumorsDignoses::class);
    }
     public function procedureDetails()
    {
        return $this->morphMany(ProcedureDetails::class, 'detailable');
    }
}
