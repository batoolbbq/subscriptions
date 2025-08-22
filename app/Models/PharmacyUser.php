<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PharmacyUser extends Model
{
    use HasFactory;
    
    protected $table = 'pharmacy_doctors';
    protected $guarded = [];
    
    public function pharmacy() {
        return $this->belongsTo(Pharmacy::class);

    }

    
}
