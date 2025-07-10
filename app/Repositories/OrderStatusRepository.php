<?php

namespace App\Repositories;

use App\Contracts\ModalInterface;
use App\Models\OrderStatus;

class OrderStatusRepository extends BaseRepository implements ModalInterface
{
    public function model()
    {
        return OrderStatus::class;
    }
}
