<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function generalpractitioners() {
        return $this->belongsTo(Generalpractitioner::class);

    }
    public function customers() {
        return $this->belongsTo(Customer::class);

    }
    public function specialistdoctors() {
        return $this->belongsTo(Specialistdoctor::class);

    }
    public function chronicdiseases() {
        return $this->belongsTo(Chronicdiseases::class);

    }
    public function retireds() {
        return $this->belongsTo(retired::class);

    }
    public function permissionDispenseMedications() {
        return $this->belongsTo(PermissionDispenseMedication::class);
    }
}
