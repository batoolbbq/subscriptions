<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activesubstance extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'name', 'chronicdiseases_id'
    ];

    public function chronicdiseases()
    {
        return $this->belongsTo(Chronicdiseases::class);
    }
    public function medicines()
    {
        return $this->hasMany(Medicine::class);
    }
    public function medicalprofiles()
    {
        return $this->hasMany(Medicalprofile::class);
    }
    public function cliamprescriptions()
    {
        return $this->hasMany(Cliamprescriptions::class);
    }
}
