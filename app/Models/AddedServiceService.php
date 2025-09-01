<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddedServiceService extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'price', 'status'];

    public function insuranceAgents()
    {
        return $this->belongsToMany(InsuranceAgent::class, 'added_service_service_insurance_agent');
    }
}
