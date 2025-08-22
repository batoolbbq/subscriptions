<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class subscription_type extends Model
{
    use HasFactory;


      public function values()
    {
        return $this->hasMany(subscription_values::class, 'subscription_type');
    }

}
