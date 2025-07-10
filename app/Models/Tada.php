<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tada extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'user_id',
        'tadatype_id',
        'from',
        'to',
        'date',
        'daily_allowance',
        'amount',
        'photo',
        'lat',
        'long',
        'expense_name',
        'km',
        'per_km_price',
        'comment',
        'is_confirm',

    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    public const SEARCH_FIELDS = ['type'];

    public function tadatype()
    {
        return $this->belongsTo(TadaType::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
