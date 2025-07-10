<?php

namespace App\Repositories;

use App\Contracts\ModalInterface;
use App\Models\VariantType;

class VariantTypeRepository extends BaseRepository implements ModalInterface
{
    public function model()
    {
        return VariantType::class;
    }
}
