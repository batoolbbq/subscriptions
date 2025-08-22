<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Martyrs_wounded extends Model
{

    use HasFactory;

    protected $table = 'beneficiaries';

    public function customer(){
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }
   
}