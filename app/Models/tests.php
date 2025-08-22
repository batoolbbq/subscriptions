<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tests extends Model
{
    use HasFactory;

    protected $guarded = [];

    
    public function test_categories_id() {
        return $this->belongsTo(testCategories::class);

    }


}
