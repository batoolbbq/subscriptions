<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personalphotos extends Model
{
    use HasFactory;
    public function retireds() {
        return $this->belongsTo(retired::class);

    }
    public function customers() {
        return $this->belongsTo(Customer::class);

    }

   
}
