<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Product extends Model
{
    use HasFactory;
    use HasSlug;

    protected $fillable = [
        'category_type_id',
        'category_id',
        'is_parent',
        'product_id',
        'name',
        'code',
        'image',
        'alt_tag',
        'description',
        'specification',
        'is_fast_selling',
        'stock_status',
        'gst',
        'cgst',
        'sgst',
        'sort_order',
        'status',
        'seo_keyword',
        'meta_title',
        'meta_keyword',
        'meta_description',
        'schema_tag',
    ];

    protected $appends = ['qty_stock'];

    public function getQtyStockAttribute()
    {
        return ProductStockLog::where('product_id', $this->id)->sum('quantity');
    }

    /**
     * Get the description attribute.
     *
     * @param  string  $value
     * @return array
     */
    public function getSpecificationAttribute($value)
    {
        return json_decode($value, true);
    }

    /**
     * Set the description attribute.
     *
     * @param  array  $value
     * @return void
     */
    public function setSpecificationAttribute($value)
    {
        // Reset the keys of the array
        $value = array_values($value);

        // JSON encode the array
        $this->attributes['specification'] = json_encode($value);
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('product_name')
            ->saveSlugsTo('seo_keyword');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function categoryType()
    {
        return $this->belongsTo(CategoryType::class);
    }

    public function productimage()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function product_variant($id = null)
    {
        if ($id) {
            return $this->hasMany(ProductVariant::class)->where('id', $id)->first();
        }

        return $this->hasMany(ProductVariant::class);
    }

    public function stockLogs()
    {
        return $this->hasMany(ProductStockLog::class, 'id', 'product_id');
    }

    public function qtyType()
    {
        return $this->hasOne(VariantType::class, 'id', 'qty_type');
    }

    public function variantPrices(): HasMany
    {
        return $this->hasMany(ProductVariantPrice::class, 'product_id', 'id');
    }
}
