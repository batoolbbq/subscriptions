<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstitucionSheetRow extends Model
{
    use HasFactory;

    protected $table = 'institucion_sheet_rows';

    protected $fillable = [
        'institucion_id',
        'name',
        'national_id',
        'family_registry_no',
        'account_no',
    ];

    public function institucion()
    {
        return $this->belongsTo(Institucion::class);
    }
}
