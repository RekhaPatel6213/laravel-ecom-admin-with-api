<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Category extends Model
{
    use HasSlug;
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name',
        'is_parent',
        'parent_category_id',
        'category_type_id',
        'description',
        'image',
        'app_image',
        'banner_image',
        'seo_keyword',
        'meta_title',
        'meta_keyword',
        'meta_description',
        'schema_tag',
        'sort_order',
        'status',
    ];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('seo_keyword');
    }

    public function child_category()
    {
        return $this->hasMany(Category::class, 'parent_category_id', 'id')->select('id', 'parent_category_id', 'name', 'image', 'alt_tag', 'header_title', 'icon', 'icon_alt_tag', 'header_image', 'header_alt_tag', 'seo_keyword')->where('status', 1);
    }

    public function parent_category()
    {
        return $this->belongsTo(Category::class, 'parent_category_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'id', 'parent_category_id');
    }

    public function categoryType()
    {
        return $this->belongsTo(CategoryType::class);
    }

    public function product()
    {
        return $this->hasMany(Product::class)->where('status', 1); // ->where('mrp', '>', 0);
    }
}
