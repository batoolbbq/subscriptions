<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;

    public $incrementing = false;           // لأننا نستخدم id خارجي
    protected $keyType = 'int';
    protected $fillable = ['id','name'];

    public function branches()
    {
        return $this->hasMany(BankBranch::class, 'bank_id');
    }

    public function customers()
    {
        return $this->hasMany(Customer::class, 'bank_id');
    }
}


