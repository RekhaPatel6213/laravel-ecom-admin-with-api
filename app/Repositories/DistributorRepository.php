<?php

namespace App\Repositories;

use App\Contracts\ModalInterface;
use App\Models\Distributor;

class DistributorRepository extends BaseRepository implements ModalInterface
{
    public function model()
    {
        return Distributor::class;
    }
}
