<?php

namespace App\Repositories;

use App\Models\CategoryType;

class CategoryTypeRepository extends BaseRepository
{
    public function model()
    {
        return CategoryType::class;
    }
}
