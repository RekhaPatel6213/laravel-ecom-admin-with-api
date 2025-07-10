<?php

namespace App\Repositories;

use App\Contracts\ModalInterface;
use App\Models\Zone;

class ZoneRepository extends BaseRepository implements ModalInterface
{
    public function model()
    {
        return Zone::class;
    }
}
