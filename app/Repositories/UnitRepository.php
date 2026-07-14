<?php

namespace App\Repositories;

use App\Models\Unit;

class UnitRepository extends BaseRepository
{
    protected function modelClass(): string
    {
        return Unit::class;
    }

    public function getAll($perPage = 20)
    {
        return Unit::orderBy('name')->paginate($perPage);
    }
}
