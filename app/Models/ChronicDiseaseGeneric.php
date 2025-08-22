<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChronicDiseaseGeneric extends Model
{
    use HasFactory;

    protected $guarded=[];


    protected $table = 'chronic_disease_generics';



    public function chronic(){
        return $this->belongsTo(Chronicdiseases::class, 'chronicdiseases_id');
    }

}
