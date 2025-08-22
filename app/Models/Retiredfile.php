<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Retiredfile extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'branch', 'office', 'gender', 'pensionNo', 
        'pensionNo', 'name', 'pensionStatus',
        'pensionType', 'classCode', 'nationalNumber',
        'dateofBirth',
       ];

      
}
