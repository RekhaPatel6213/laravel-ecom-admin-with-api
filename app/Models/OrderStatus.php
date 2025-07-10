<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderStatus extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'order_status_name',
        'status',
    ];

    public const SEARCH_FIELDS = ['order_status_name'];

    public function orders()
    {
        return $this->hasMany(Order::class, 'orderstatus_id', 'id');
    }
}
