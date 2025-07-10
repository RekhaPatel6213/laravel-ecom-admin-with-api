<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Distributor extends Model
{
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'mobile',
        'shop_name',
        'is_interested',
        'contact',
        'customer_code',
        'country_id',
        'state_id',
        'city_id',
        // 'area_id',
        'current_dealership',
        'area_of_operation',
        'address',
        'pincode',
        'zone_id',
        'status',
        'pan_doc',
        'pan_no',
        'vat_tin_no',
        'cst_gst_no',
        'gst_doc',
    ];

    public function addresses(): MorphMany
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }

    public function meetingType(): BelongsTo
    {
        return $this->belongsTo(MeetingType::class, 'is_interested', 'id');
    }
}
