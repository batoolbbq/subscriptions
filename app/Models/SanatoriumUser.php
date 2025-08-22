<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SanatoriumUser extends Model
{
    use HasFactory;

    protected $table = "sanatorium_doctors";
}