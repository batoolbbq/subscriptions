<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentDueType extends Model
{
    
    use HasFactory;

    protected $table = 'payment_due_types';

    protected $fillable = ['name'];

  
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'payment_due_type_id');
    }
}


