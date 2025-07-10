<?php

namespace App\Repositories;

use App\Contracts\ModalInterface;
use App\Models\Country;

class CountryRepository extends BaseRepository implements ModalInterface
{
    public function model()
    {
        return Country::class;
    }
}
