<?php

namespace App\Repositories;

use App\Contracts\ModalInterface;
use App\Models\City;

class CityRepository extends BaseRepository implements ModalInterface
{
    public function model()
    {
        return City::class;
    }
}
