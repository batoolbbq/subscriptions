<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pharmacy extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
     'pharmacyName'
     ,'address','city','authorizeName','email',
     'nationalNumber','phoneNumber',
     'licenseNumber','icenseNumberFile',
     'qualification','pharmacyPermissionFile',
     'profilePictureFile','chamberofCommerceFile',
     'supplierRegisterFile','textPaymentFile',
     'pharmaceuticaAgentslFiles','pharmacySpace','pharmacyStatus'
    ];

    public function cities() {
        return $this->belongsTo(City::class);

    }
    public function permissionDispenseMedications() {
        return $this->belongsTo(PermissionDispenseMedication::class);
    }
    public function permissionDispenseClaims() {
        return $this->belongsTo(PermissionDispenseClaims::class);
    }
    
    public function users(){
        return $this->belongsToMany(User::class, PharmacyUser::class, 'pharmacy_id', 'user_id');
    }
}
