<?php

namespace App\Repositories;

use App\Contracts\ModalInterface;
use App\Models\MeetingType;

class MeetingTypeRepository extends BaseRepository implements ModalInterface
{
    public function model()
    {
        return MeetingType::class;
    }
}
