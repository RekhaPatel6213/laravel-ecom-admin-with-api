<?php

namespace App\Repositories;

use App\Contracts\ModalInterface;
use App\Models\Route;

class RouteRepository extends BaseRepository implements ModalInterface
{
    public function model()
    {
        return Route::class;
    }
}
