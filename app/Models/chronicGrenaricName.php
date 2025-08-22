<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class chronicGrenaricName extends Model
{
    use HasFactory;
    
    public function genaric_name() {
        return $this->belongsTo(genaricName::class , 'genaric_names_id' , 'id');
    }
}
