<?php

namespace App\Repositories\Contracts;

interface BaseRepositoryInterface
{
    public function findById(string $id): ?object;
    public function create(array $data): object;
    public function update(string $id, array $data): ?object;
    public function delete(string $id): bool;
}
