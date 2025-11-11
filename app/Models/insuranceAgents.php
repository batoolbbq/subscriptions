<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class insuranceAgents extends Model
{
    use HasFactory;
     protected $table = 'insurance_agents';
    protected $guarded = [];
    
    public function cities()
    {
        return $this->belongsTo(City::class);
    }
    
    public function municipals()
    {
        return $this->belongsTo(Municipal::class);
    }
// public function users()
// {
//     return $this->hasMany(User::class, 'insurance_agents_id');
// }

 public function companies()
{
    return $this->hasMany(InsuranceAgentCompany::class, 'insurance_agent_id');
}


public function users()
{
    return $this->belongsToMany(User::class, 'insurance_agent_user', 'insurance_agent_id', 'user_id');
}




public function addedServices()
{
    return $this->belongsToMany(AddedServiceService::class, 'added_service_service_insurance_agent');
}


}
