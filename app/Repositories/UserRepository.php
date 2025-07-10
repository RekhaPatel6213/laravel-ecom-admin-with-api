<?php

namespace App\Repositories;

use App\Contracts\ModalInterface;
use App\Models\User;

class UserRepository extends BaseRepository implements ModalInterface
{
    public function model()
    {
        return User::class;
    }
}
