<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
     'activesubstances_id','product','chronicdiseases_id','product_type','measruingunit','duration'
    ];

    public function chronicdiseases() {
        return $this->belongsTo(Chronicdiseases::class);

    }
    public function activesubstances() {
        return $this->belongsTo(Activesubstance::class);

    }
}
