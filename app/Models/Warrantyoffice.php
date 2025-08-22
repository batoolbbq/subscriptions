<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warrantyoffice extends Model
{

    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
     'name','code'
    ];

   
    public function retireds() {
        return $this->hasMany(retired::class);
    }
    public function guarantybranches() {
        return $this->belongsTo(guarantybranch::class);

    }
}
