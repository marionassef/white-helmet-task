<?php

namespace App\Services;

use App\Repositories\CommentRepositoryInterface;

class CommentService implements CommentServiceInterface
{
    /**
     * @var CommentRepositoryInterface
     */
    private CommentRepositoryInterface $commentRepository;

    public function __construct(CommentRepositoryInterface $repository)
    {
        $this->commentRepository = $repository;
    }

    /**
     * @param $data$data
     * @return mixed
     */
    public function list(array $data = null): mixed
    {
        return $this->commentRepository->findAllWithPaging(['post_id' => $data['post_id']], [], $data['limit'] ?? 10 , $data['offset'] ?? 0);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function store($data): mixed
    {
        $data['author_id'] = 'b1ded1b1-5b7c-4b39-a4a0-44a4692e878f';
        return $this->commentRepository->create($data);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function getOneBy($data): mixed
    {
        return $this->commentRepository->findOneBy(['id'=> $data['id'], 'post_id' => $data['post_id']]);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function update($data): mixed
    {
        $item = $this->commentRepository->findOneBy(['id'=> $data['id']]);
        $this->commentRepository->update($item, $data);
        return $item;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id): mixed
    {
        return $this->commentRepository->delete($id);
    }
}
