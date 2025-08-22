<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class beneficiariesSupCategories extends Model
{
    use HasFactory;


    protected $table = 'beneficiaries_sup_categories';
    protected $fillable = ['name','type','code','beneficiaries_categories_id','status'];

    public function category()
    {
        return $this->belongsTo(beneficiariesCategories::class, 'beneficiaries_categories_id');
    }
}
