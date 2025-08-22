<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionDispenseClaims extends Model
{    public $timestamps = false;
    use HasFactory;

    
    public function customers() {
        return $this->belongsTo(Customer::class);

    }
    public function cliamprescriptions() {
        return $this->belongsTo(Cliamprescriptions::class);
    }
    public function pharmacy() {
        return $this->belongsTo(Pharmacy::class);
    }}
