<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class orderGenaricName extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $guarded=[];


    public function genaric_names()
    {
        return $this->belongsTo(genaricName::class);
    }
    public function orderBrandNameStock()
    {
        return $this->hasMany(orderBrandNameStock::class , 'orderGenaricName_id' , 'id');
    }
}
