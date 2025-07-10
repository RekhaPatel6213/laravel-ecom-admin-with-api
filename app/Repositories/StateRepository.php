<?php

namespace App\Repositories;

use App\Contracts\ModalInterface;
use App\Models\State;

class StateRepository extends BaseRepository implements ModalInterface
{
    public function model()
    {
        return State::class;
    }
}
