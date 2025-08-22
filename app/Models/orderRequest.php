<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class orderRequest extends Model
{   
    use SoftDeletes;
    use HasFactory;

     public function pharmacy() {
        return $this->belongsTo(Pharmacy::class ,  'pharmacies_id', 'id' );
    }

     public function orderGenaricName() {
        return $this->hasMany(orderGenaricName::class ,  'order_requests_id', 'id' );
    }

     public function pharmacy_branch() {
        return $this->belongsTo(PharmacyUser::class ,  'pharmacy_branch_id', 'id' );
    }
}
