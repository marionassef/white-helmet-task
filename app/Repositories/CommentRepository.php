<?php

namespace App\Repositories;

use App\Models\Comment;

class CommentRepository extends AbstractRepository implements CommentRepositoryInterface
{
    public function __construct()
    {
        $this->model = new Comment();
    }
}
