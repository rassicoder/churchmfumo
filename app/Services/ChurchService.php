<?php

namespace App\Services;

use App\Models\Church;
use App\Repositories\Contracts\ChurchRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ChurchService
{
    public function __construct(private readonly ChurchRepositoryInterface $churches)
    {
    }

    public function list(array $filters, int $perPage): LengthAwarePaginator
    {
        return $this->churches->paginateWithFilters($filters, $perPage);
    }

    public function create(array $data): Church
    {
        if (! isset($data['status'])) {
            $data['status'] = config('church.default_status', 'active');
        }

        /** @var Church $church */
        $church = $this->churches->create($data);

        return $church->load('pastor:id,name,email');
    }

    public function show(string $id): ?Church
    {
        /** @var Church|null $church */
        $church = $this->churches->findById($id);

        return $church?->load('pastor:id,name,email');
    }

    public function update(string $id, array $data): ?Church
    {
        /** @var Church|null $church */
        $church = $this->churches->update($id, $data);

        return $church?->load('pastor:id,name,email');
    }

    public function delete(string $id): bool
    {
        return $this->churches->delete($id);
    }
}
