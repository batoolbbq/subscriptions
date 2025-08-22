<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Handingoutcards extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'phonenumber',
        'nationalnumber',
        'pensionnumber',
        'messagestatus',
        'messagesdescription',
        'date',

    ];
}
