<?php

namespace App\Http\Repository;

use App\Models\Option;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class OptionRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = Option::class;
    }

     public function getPaginatedOptions(array $select = ['*'], array $relationships = []): LengthAwarePaginator
    {
        return $this->model::select($select)
            ->with($relationships)
            ->paginate(20);
    }
}
