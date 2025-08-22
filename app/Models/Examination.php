<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Examination extends Model
{
    use HasFactory, SoftDeletes;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function surgeries()
    {
        return $this->hasOne(Surgery::class);
    }

    public function medicalExamination()
    {
        return $this->belongsTo(medicalexamination::class, 'medicalexamination_id', 'id');
    }

    public function approvalRequest()
    {
        return $this->hasOne(ApprovalRequest::class);
    }

    public function procedureDetails()
    {
        return $this->morphMany(ProcedureDetails::class, 'detailable');
    }
}
