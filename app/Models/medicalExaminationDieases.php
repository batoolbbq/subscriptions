<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class medicalExaminationDieases extends Model
{
    use HasFactory;
    protected $guarded = [];
    
    
       
    public function chronicdiseases() {
        return $this->belongsTo(Chronicdiseases::class);

    }
}
