<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Qr_batchNumber_storge extends Model
{
    use HasFactory;

    protected $table = 'qr_batch_number_storges';

    protected $fillable = [
        'uuid',
        'patsh_number_id',
        'genaric_name_id',
        'supplier_brand_name_id',
        'old_storage',
        'added_storage',
        'new_storage',
    ];


    public function patsh_number()
    {
        return $this->belongsTo(patshNumber::class);
    }
}
