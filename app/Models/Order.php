<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'distributor_id',
        'shop_id',
        'meeting_id',
        'order_no',
        'invoice_no',
        'firstname',
        'lastname',
        'email',
        'mobile',
        'latitude',
        'longitude',
        'shipping_address_id',
        'shipping_firstname',
        'shipping_lastname',
        'shipping_email',
        'shipping_mobile',
        'shipping_country_name',
        'shipping_state_name',
        'shipping_city_name',
        'shipping_pincode',
        'billing_address_id',
        'billing_firstname',
        'billing_lastname',
        'billing_email',
        'billing_mobile',
        'billing_country_name',
        'billing_state_name',
        'billing_city_name',
        'billing_pincode',
        'payment_method',
        'payment_code',
        'orderstatus_id',
        'is_paid',
        'sub_total',
        'total_gst',
        'cgst',
        'sgst',
        'shipping_charge',
        'grand_total',
        'total_quantity',
        'order_pdf',
        'coupon_id',
        'coupon_code',
        'coupon_discount',
        'payment_date',
    ];

    public const SEARCH_FIELDS = ['order_no', 'invoice_no', 'created_at', 'total_quantity', 'grand_total'];

    public function orderproduct()
    {
        return $this->hasMany(OrderProduct::class);
    }

    public function orderhistory()
    {
        return $this->hasMany(OrderHistory::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function distributor()
    {
        return $this->belongsTo(Distributor::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }

    public function orderstatus()
    {
        return $this->belongsTo(OrderStatus::class);
    }

    public function billingaddress()
    {
        return $this->belongsTo(Address::class, 'billing_address_id', 'id');
    }

    public function shippingaddress()
    {
        return $this->belongsTo(Address::class, 'shipping_address_id', 'id');
    }
}
