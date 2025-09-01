<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankBranch extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'int';
    protected $fillable = ['id','bank_id','name'];

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }

    public function customers()
    {
        return $this->hasMany(Customer::class, 'bank_branch_id');
    }
}


