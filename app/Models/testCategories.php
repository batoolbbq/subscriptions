<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class testCategories extends Model
{
    use HasFactory;

    public function test_categories_id() {
        return $this->hasMany(tests::class);
    }


}
