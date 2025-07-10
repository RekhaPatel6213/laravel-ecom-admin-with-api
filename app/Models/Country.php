<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id',
        'name',
        'code',
        'sort_order',
        'status',
    ];

    public const SEARCH_FIELDS = ['name', 'code'];

    public function states(): HasMany
    {
        return $this->hasMany(State::class);
    }
}
