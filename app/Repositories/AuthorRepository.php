<?php

namespace App\Repositories;

use App\Models\User;

class AuthorRepository extends AbstractRepository implements AuthorRepositoryInterface
{
    public function __construct()
    {
        $this->model = new User();
    }
}
