<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TadaType extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name',
        'is_date',
        'is_amount',
        'is_photo',
        'is_location',
        'is_from_to_location',
        'is_km',
        'is_expense_name',
        'status',
    ];

    public const SEARCH_FIELDS = ['name'];

    public function tadas()
    {
        return $this->hasMany(Tada::class, 'tadatype_id', 'id');
    }
}
