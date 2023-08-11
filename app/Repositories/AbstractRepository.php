<?php

namespace App\Repositories;

use App\Exceptions\CustomQueryException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

 class AbstractRepository implements AbstractRepositoryInterface
{
    public object $model;

     /**
      * @param $data
      * @return mixed
      * @throws CustomQueryException
      */
     public function create($data): mixed
     {
        try {
            return $this->model->query()->create($data);
        } catch (QueryException $exception) {
            Log::debug($exception);
            throw new CustomQueryException($exception->getMessage());
        }
    }

     /**
      * @param $item
      * @param $data
      * @return mixed
      * @throws CustomQueryException
      */
     public function update($item, $data): mixed
     {
        try {
            return $item->update($data);
        } catch (QueryException $exception) {
            Log::debug($exception);
            throw new CustomQueryException($exception->getMessage());
        }
    }

     /**
      * @param $itemIfExist
      * @param $data
      * @return mixed
      * @throws CustomQueryException
      */
     public function updateOrCreate($itemIfExist, $data): mixed
     {
        try {
            return $this->model->query()->updateOrCreate($itemIfExist,$data);
        } catch (QueryException $exception) {
            Log::debug($exception);
            throw new CustomQueryException($exception->getMessage());
        }
    }

     /**
      * @param $filters
      * @param $with
      * @param $returnResults
      * @param $orderBy
      * @param $direction
      * @return mixed
      * @throws CustomQueryException
      */
     public function findAll($filters = [], $with = [], $returnResults = true, $orderBy = 'created_at', $direction = 'DESC'): mixed
     {
        try {
            $query = $this->model->query()
                ->where($filters)
                ->with($with)
                ->orderBy($orderBy, $direction);

            return $returnResults ? $query->get() : $query;
        } catch (QueryException $exception) {
            Log::debug($exception);
            throw new CustomQueryException($exception->getMessage());
        }
    }

     /**
      * @param $filters
      * @param $with
      * @param $limit
      * @param $offset
      * @param $returnResults
      * @param $orderBy
      * @param $direction
      * @return mixed
      * @throws CustomQueryException
      */
     public function findAllWithPaging($filters = [], $with = [], $limit = 10, $offset = 0, $returnResults = true, $orderBy = 'created_at', $direction = 'DESC'): mixed
     {
        try {
            $query = $this->model->query()
                ->where($filters)
                ->with($with)
                ->orderBy($orderBy, $direction);

            return $returnResults ? $query->limit($limit)->offset($offset)->get() : $query;
        } catch (QueryException $exception) {
            Log::debug($exception);
            throw new CustomQueryException($exception->getMessage());
        }
    }

     /**
      * @param array $filters
      * @param array $with
      * @return mixed
      * @throws CustomQueryException
      */
     public function findOneBy(array $filters = [], array $with = []): mixed
     {
        try {
            return $this->model->query()->where($filters)->with($with)->first();
        } catch (QueryException $exception) {
            Log::debug($exception);
            throw new CustomQueryException($exception->getMessage());
        }
    }

     /**
      * @param array $filters
      * @param int $perPage
      * @return LengthAwarePaginator
      */
     public function findAllWithPagination(array $filters, int $perPage = 15): LengthAwarePaginator
     {
         return $this->model->query()->where($filters)->paginate($perPage);
     }

     /**
      * @param array $filters
      * @param array $with
      * @return mixed
      * @throws CustomQueryException
      */
     public function findOneByOrFail(array $filters = [], array $with = []): mixed
     {
        try {
            return $this->model->query()->where($filters)->with($with)->firstOrFail();
        } catch (QueryException $exception) {
            Log::debug($exception);
            throw new CustomQueryException($exception->getMessage());
        }
    }

     /**
      * @param $id
      * @return mixed
      * @throws CustomQueryException
      */
     public function delete($id): mixed
     {
        try {
            return $this->model->destroy($id);
        } catch (QueryException $exception) {
            Log::debug($exception);
            throw new CustomQueryException($exception->getMessage());
        }
    }
}
