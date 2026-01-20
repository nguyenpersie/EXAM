<?php

namespace App\Http\Services;

use App\Http\Repository\OptionRepository;
use App\Models\Option;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class OptionService
{
    protected OptionRepository $optionRepository;

    public function __construct(OptionRepository $optionRepository)
    {
        $this->optionRepository = $optionRepository;
    }

    public function getPaginatedOptions(): LengthAwarePaginator
    {
        return $this->optionRepository->getPaginatedOptions();
    }
}
