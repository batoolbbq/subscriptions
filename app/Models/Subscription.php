<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
       use HasFactory;

    protected $table= 'subscription33';

    protected $fillable = ['name', 'beneficiaries_categories_id', 'status'];

      public function values()
    {
        return $this->hasMany(subscription_values::class, 'subscription_id');
    }
        public function beneficiariesCategory()
        {
            return $this->belongsTo(\App\Models\beneficiariesCategories::class, 'beneficiaries_categories_id');
        }

}
