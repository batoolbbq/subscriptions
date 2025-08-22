<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chronicdiseases extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
     'name',
    ];
    public function customer() {
        return $this->hasMany(Customer::class);
    }
    public function Activesubstances() {
        return $this->hasMany(Activesubstance::class);
    }
    public function medicines() {
        return $this->hasMany(Medicine::class);
    }
    public function medicalprofiles() {
        return $this->hasMany(Medicalprofile::class);
    }
    public function assigns() {
        return $this->hasMany(Assign::class);
    }

    public function chronicGrenaricName() {
        return $this->hasMany(chronicGrenaricName::class);
    }

}
