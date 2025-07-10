<?php

namespace App\Repositories;

use App\Contracts\ModalInterface;
use App\Models\Coupon;

class CouponRepository extends BaseRepository implements ModalInterface
{
    public function model()
    {
        return Coupon::class;
    }
}
