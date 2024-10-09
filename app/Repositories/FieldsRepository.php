<?php

namespace App\Repositories;

use App\Models\Field;
use Illuminate\Support\Collection;

class FieldsRepository
{
    /**
     * @param array $pipedriveIds
     * @return Collection
     */
    public function findMany(array $pipedriveIds): Collection
    {
        return Field::whereIn('pipedrive_id', $pipedriveIds)->get();
    }

    /**
     * @param int $pipedriveId
     * @return Field
     */
    public function findOne(int $pipedriveId): Field
    {
        return Field::firstWhere('pipedrive_id', $pipedriveId);
    }
}
