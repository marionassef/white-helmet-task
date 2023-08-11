<?php

namespace App\Services;

use App\Repositories\PostRepositoryInterface;

class PostService implements PostServiceInterface
{
    /**
     * @var PostRepositoryInterface
     */
    private PostRepositoryInterface $postRepository;


    public function __construct(PostRepositoryInterface $repository)
    {
        $this->postRepository = $repository;
    }

    /**
     * @param array|null $data
     * @return mixed
     */
    public function list(array $data = null): mixed
    {
        return $this->postRepository->findAllWithPaging([], [], $data['limit'] ?? 10 , $data['offset'] ?? 0);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function store($data): mixed
    {
        $data['author_id'] = 'b1ded1b1-5b7c-4b39-a4a0-44a4692e878f';
        return $this->postRepository->create($data);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getOneBy($id): mixed
    {
        return $this->postRepository->findOneBy(['id' => $id]);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function update($data): mixed
    {
        $item = $this->postRepository->findOneBy(['id' => $data['id']]);
        $this->postRepository->update($item, $data);
        return $item;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id): mixed
    {
        return $this->postRepository->delete($id);
    }
}
