<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository implements BaseRepositoryInterface
{
    public function __construct(protected Model $model)
    {
    }

    public function findById(string $id): ?object
    {
        return $this->model->newQuery()->find($id);
    }

    public function create(array $data): object
    {
        return $this->model->newQuery()->create($data);
    }

    public function update(string $id, array $data): ?object
    {
        $record = $this->findById($id);

        if (! $record) {
            return null;
        }

        $record->update($data);

        return $record;
    }

    public function delete(string $id): bool
    {
        $record = $this->findById($id);

        if (! $record) {
            return false;
        }

        return (bool) $record->delete();
    }
}
