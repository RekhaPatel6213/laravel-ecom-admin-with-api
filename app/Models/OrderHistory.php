<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'orderstatus_id',
        'comment',
    ];

    public function orderstatus()
    {
        return $this->belongsTo(OrderStatus::class);
    }
}
