<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkCategory extends Model
{
    use HasFactory;
        protected $fillable = ['name', 'status'];


          public function institucion()
    {
        return $this->hasmany(Institucion::class, 'work_categories_id');
    }
}
