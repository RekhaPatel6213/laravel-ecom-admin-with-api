<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VariantValue extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'status',
        'sort_order',
    ];

    public const SEARCH_FIELDS = ['name'];

    public function productVariant()
    {
        return $this->hasMany(ProductVariant::class, 'variant_value', 'id');
    }
}
