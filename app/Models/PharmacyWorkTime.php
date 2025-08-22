<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PharmacyWorkTime extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'saturday_Open','saturday_Close',
        'sunday_Open','sunday_Close',
     
        'monday_Open','monday_Close',
        'tuesday_Open','tuesday_Close',
        'wednesday_Open','wednesday_Close',
        'thursday_Open','thursday_Close',
        'friday_Open','friday_Close',
       ];
   
}
