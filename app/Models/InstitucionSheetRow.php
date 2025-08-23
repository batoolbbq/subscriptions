<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstitucionSheetRow extends Model
{
    protected $fillable = [
        'institucion_id',
        'name',
        'father_name',
        'last_name',
        'national_id',
        'family_registry_no',
        'insured_no',
        'pension_no',
        'account_no',
        'total_pension',
    ];

    protected $casts = [
        'total_pension' => 'decimal:2',
    ];

    public function institucion()
    {
        return $this->belongsTo(Institucion::class);
    }
}

