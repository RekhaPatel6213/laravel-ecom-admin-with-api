<?php

namespace App\Models;

use App\Models\Scopes\UserScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Meeting extends Model
{
    protected $fillable = [
        'user_id',
        'distributor_id',
        'shop_id',
        'type_id',
        'route_id',
        'meeting_date',
        'start_time',
        'start_latitude',
        'start_longitude',
        'end_time',
        'end_latitude',
        'end_longitude',
        'comments',
        'purpose',
        'attachment1',
        'attachment2',
        'attachment3',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'meeting_date' => 'date',
        ];
    }

    public function distributor(): BelongsTo
    {
        return $this->belongsTo(Distributor::class);
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(MeetingType::class, 'type_id', 'id');
    }

    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($instance) {
            $instance->user_id = Auth::user()->id;
            $instance->meeting_date = now();
        });
    }

    protected static function booted()
    {
        static::addGlobalScope(new UserScope);
    }
}
