<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Institucion extends Model
{
    
    use HasFactory;

    protected $table = 'institucions';
    protected $fillable = [
        'name',
        'commercial_number',
        'work_categories_id',
        'subscriptions_id',
        'insurance_agent_id',
        'status',
        'license_number',
        'commercial_record',
    ];

  

  public function subscription()
{
    return $this->belongsTo(Subscription::class, 'subscriptions_id');
}


    public function workCategory()
    {
        return $this->belongsTo(WorkCategory::class, 'work_categories_id');
    }

    public function insuranceAgent()
{
    return $this->belongsTo(insuranceAgents::class, 'insurance_agent_id');
}



}
