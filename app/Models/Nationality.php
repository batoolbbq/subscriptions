<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nationality extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
     'name',
    ];

    public function customer() {
        return $this->hasMany(Customer::class);
    }
}
