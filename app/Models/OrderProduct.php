<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    protected $fillable = [
        'order_id',
        'category_id',
        'product_id',
        'product_name',
        'product_code',
        'variant_id',
        'variant_type',
        'variant_value',
        'product_quantity',
        'product_image',
        'product_mrp',
        'product_selling_price',
        'gst_per',
        'gst_val',
        'total_amount',
        'amount_without_gst',
        'total_gst_val',
        'with_out_gst_price',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function productVariant()
    {
        return $this->hasMany(ProductVariant::class, 'id', 'variant_id');
    }
}
