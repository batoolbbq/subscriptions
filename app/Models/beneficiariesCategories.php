<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class beneficiariesCategories extends Model
{
    use HasFactory;
    protected $fillable = ['name','code','status'];


      public function supCategories()
    {
        return $this->hasMany(beneficiariesSupCategories::class, 'beneficiaries_categories_id');
    }

      public function customer()
    {
        return $this->hasMany(Customer::class, 'beneficiaries_categories_id');
    }
}
