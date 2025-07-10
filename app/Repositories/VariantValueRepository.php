<?php

namespace App\Repositories;

use App\Contracts\ModalInterface;
use App\Models\VariantValue;

class VariantValueRepository extends BaseRepository implements ModalInterface
{
    public function model()
    {
        return VariantValue::class;
    }
}
