<?php


namespace App\Repositories;


interface AbstractRepositoryInterface
{
    public function create(array $data);

    public function update($item,array $data);

    public function updateOrCreate($itemIfExist, array $data);

    public function findAll(array $filters = [], array $with = [], bool $returnResults = true, string $orderBy = 'id', string $direction = 'DESC');

    public function findAllWithPaging( array $filters = [], array $with = [], int $limit = 10, int $offset = 0, bool $returnResults = true, string $orderBy = 'id', string $direction = 'DESC');

    public function findOneBy(array $filters = [], array $with = []);

    public function findOneByOrFail(array $filters = [], array $with = []);

    public function delete($id);
}
