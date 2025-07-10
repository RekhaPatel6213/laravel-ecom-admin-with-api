<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait CommonScopeTrait
{
    public function scopeLoginUser(Builder $builder, int $userId): void
    {
        $builder->where('user_id', $userId);
    }

    public function scopeDistributorUser(Builder $builder, ?int $distributorId = null): void
    {
        $builder->where('distributor_id', $distributorId);
    }

    public function scopeShopUser(Builder $builder, ?int $shopId = null): void
    {
        if (($shopId !== null)) {
            $builder->where('shop_id', $shopId);
        } else {
            $builder->whereNull('shop_id');
        }
    }
}
