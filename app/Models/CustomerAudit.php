<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerAudit extends Model
{
    use HasFactory;

    protected $table = "customer_audit";
    
    public function customers(){
        return $this->belongsTo(Customer::class);
    }
    public function users(){
        return $this->belongsTo(User::class);
    }
}