<?php

namespace App\Repositories;

interface RepositoryInterface
{
    public function findAll();

    public function findById(int $id);

    public function save($entity);

    public function delete(int $id);
}
