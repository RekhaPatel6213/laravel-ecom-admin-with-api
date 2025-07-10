<?php

namespace App\Repositories;

use App\Contracts\ModalInterface;
use App\Models\TadaType;

class TadaTypeRepository extends BaseRepository implements ModalInterface
{
    public function model()
    {
        return TadaType::class;
    }
}
