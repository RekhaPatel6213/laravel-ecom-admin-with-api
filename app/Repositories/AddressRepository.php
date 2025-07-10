<?php

namespace App\Repositories;

use App\Contracts\ModalInterface;
use App\Models\Address;

class AddressRepository extends BaseRepository implements ModalInterface
{
    public function model()
    {
        return Address::class;
    }
}
