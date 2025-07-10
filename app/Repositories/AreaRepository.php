<?php

namespace App\Repositories;

use App\Contracts\ModalInterface;
use App\Models\Area;

class AreaRepository extends BaseRepository implements ModalInterface
{
    public function model()
    {
        return Area::class;
    }
}
