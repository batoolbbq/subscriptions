<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkplaceCode extends Model
{
    use HasFactory;

    /**
     * الأعمدة المسموح تخزينها (mass assignment)
     */
    protected $fillable = [
        'name',
        'code',
        'parent_id',
    ];

    /**
     * العلاقة: هذا الكود يتبع كود أب (Parent)
     */
    public function parent()
    {
        return $this->belongsTo(WorkplaceCode::class, 'parent_id');
    }

    /**
     * العلاقة: هذا الكود عنده أكواد أبناء (Children)
     */
    public function children()
    {
        return $this->hasMany(WorkplaceCode::class, 'parent_id');
    }
    public function institucions()
{
    return $this->hasMany(Institucion::class, 'workplace_code_id');
}

}
