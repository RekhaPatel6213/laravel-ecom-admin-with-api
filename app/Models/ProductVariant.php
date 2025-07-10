<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'product_id',
        'product_name',
        'variant_type',
        'variant_value',
        'color',
        'qty',
        'qty_type',
        'mrp',
        'sp',
    ];

    protected $appends = ['qty_stock'];

    public function getQtyStockAttribute()
    {
        return (int) ProductStockLog::where('product_variant_id', $this->id)->sum('quantity');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variantType()
    {
        return $this->hasOne(VariantType::class, 'id', 'variant_type');
    }

    public function variantValue()
    {
        return $this->hasOne(VariantValue::class, 'id', 'variant_value');
    }

    public function color()
    {
        return $this->hasOne(color::class, 'id', 'color');
    }

    public function stockLogs()
    {
        return $this->hasMany(ProductStockLog::class, 'id', 'product_variant_id');
    }

    public function variant_prices()
    {
        return $this->hasMany(ProductVariantPrice::class, 'variant_id', 'id');
    }

    public function variant_price()
    {
        return $this->hasOne(ProductVariantPrice::class, 'variant_id', 'id');
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::addGlobalScope('withQty', function (Builder $builder) {
            // $builder->where('qty', '>=', 1);
        });
    }
}
