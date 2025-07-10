<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'state_id',
        'name',
        'code',
        'sort_order',
        'status',
    ];

    public const SEARCH_FIELDS = ['name', 'code'];

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function areas(): HasMany
    {
        return $this->hasMany(Area::class);
    }
}
