<?php

namespace App\Repositories;

use App\Contracts\ModalInterface;
use App\Models\Category;

class CategoryRepository extends BaseRepository implements ModalInterface
{
    public function model()
    {
        return Category::class;
    }
}
