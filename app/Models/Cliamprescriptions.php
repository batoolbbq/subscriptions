<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliamprescriptions extends Model
{ use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'description',
        'activesubstances_id',
        'claims_id',

       ];
    public function claims()
    {
        return $this->belongsTo(Claim::class);
    }
    public function activesubstances()
    {
        return $this->belongsTo(Activesubstance::class);
    }
    public function permissionDispenseClaims() {
        return $this->belongsTo(PermissionDispenseClaims::class);
    }
}
