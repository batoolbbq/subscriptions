<?php

namespace App\Models;

use App\Models\subscription_type;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class subscription_values extends Model
{
    use HasFactory;
        protected $table = 'subscription_values';

    protected $fillable = [
        'subscription_id',
        'value',
        'is_percentage',
        'duration',
        'status',
        'subscription_type',
    ];

   

     public function subscription()
    {
        return $this->belongsTo(Subscription::class, 'subscription_id');
    }

    public function type()
    {
        return $this->belongsTo(subscription_type::class, 'subscription_type');
    }

}
