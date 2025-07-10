<?php

namespace App\Repositories;

use App\Contracts\ModalInterface;
use App\Models\Designation;

class DesignationRepository extends BaseRepository implements ModalInterface
{
    public function model()
    {
        return Designation::class;
    }
}
