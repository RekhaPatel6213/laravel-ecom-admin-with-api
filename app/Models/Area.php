<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Area extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'distributor_id',
        'name',
        'sort_order',
        'status',
    ];

    public const SEARCH_FIELDS = ['name', 'pincode'];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function distributor(): BelongsTo
    {
        return $this->belongsTo(Distributor::class);
    }
}
