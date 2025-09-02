<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceLog extends Model
{
    protected $fillable = ['user_id','customer_id','institucion_id','service_id'];

   public function performedBy()
{
    return $this->belongsTo(User::class, 'user_id');
}

    // نوع الخدمة (من جدول added_service_services)
    public function service()
    {
        return $this->belongsTo(AddedServiceService::class, 'service_id');
    }

    // المستفيد (لو ينطبق)
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // جهة العمل (لو ينطبق)
        public function institution()
    {
        return $this->belongsTo(\App\Models\Institucion::class, 'institucion_id');
    }
}
