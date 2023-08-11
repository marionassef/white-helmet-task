<?php

namespace App\Services;


interface AbstractServiceInterface
{
    public function list(array $data = null);

    public function store(array $data);

    public function getOneBy(array $data);

    public function update(array $data);

    public function delete($id);

}
