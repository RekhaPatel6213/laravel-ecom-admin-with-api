<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name',
        'code',
        'type',
        'discount',
        'min_order_value',
        'max_discount_allow',
        'start_date',
        'end_date',
        'total_coupon',
        'coupon_use_time',
        'status',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public const SEARCH_FIELDS = ['name', 'code', 'type', 'discount', 'start_date', 'end_date'];

    public function history(): HasMany
    {
        return $this->hasMany(CouponHistory::class);
    }
}
