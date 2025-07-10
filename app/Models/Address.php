<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'addressable_type',
        'addressable_id',
        'first_name',
        'last_name',
        'email',
        'mobile_no',
        'flat',
        'area',
        'landmark',
        'country_id',
        'state_id',
        'city_id',
        'area_id',
        'pincode',
        'default_address',
    ];

    /**
     * Get the parent addressable model (distributors or retailers & users).
     */
    public function addressable()
    {
        return $this->morphTo();
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }
}
