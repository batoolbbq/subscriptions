<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovalRequest extends Model
{
    use HasFactory;

    public function examination()
    {
        return $this->belongsTo(Examination::class);
    }
    public function subtype()
    {
        return $this->belongsTo(SurgerySubtype::class, 'surgery_subtype_id', 'id');
    }
}
