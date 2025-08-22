<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class supplierGenaricName extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function genaric_names()
        {
            return $this->belongsTo(genaricName::class, 'genaric_name_id', 'id');
        }
}
