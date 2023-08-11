<?php

namespace App\Repositories;

use App\Models\Post;

class PostRepository extends AbstractRepository implements PostRepositoryInterface
{
    public function __construct()
    {
        $this->model = new Post();
    }
}
