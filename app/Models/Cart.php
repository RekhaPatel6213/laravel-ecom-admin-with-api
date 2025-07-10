<?php

namespace App\Models;

use App\Traits\CommonScopeTrait;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use CommonScopeTrait;

    protected $fillable = [
        'user_id',
        'distributor_id',
        'shop_id',
        'meeting_id',
        'category_id',
        'product_id',
        'product_code',
        'variant_id',
        'quantity',
        'name',
        'mrp',
        'image',
        'selling_price',
        'gst_per',
        'gst_val',
        'cgst_per',
        'cgst_val',
        'sgst_per',
        'sgst_val',
        'with_out_gst_price',
        'amount',
        'amount_without_gst',
        'total_gst_val',
        'seo_url',
        'variant_type',
        'variant_value',
    ];

    protected $hidden = ['created_at', 'updated_at'];
}
