<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'generalpractitioners_id',
        'customers_id',
        'retireds_id',
       ];
    public function generalpractitioners()
    {
        return $this->belongsTo(Generalpractitioner::class);
    }
    public function customers()
    {
        return $this->belongsTo(Customer::class);
    }
    public function retireds()
    {
        return $this->belongsTo(retired::class);
    }
    public function cliamprescriptions()
    {
        return $this->hasMany(Cliamprescriptions::class);
    }
}
