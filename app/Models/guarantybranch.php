<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class guarantybranch extends Model
{
   
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
     'name','code'
    ];
    public function warrantyoffices() {
        return $this->hasMany(Warrantyoffice::class);
    }
    public function retireds() {
        return $this->hasMany(retired::class);
    }
}
