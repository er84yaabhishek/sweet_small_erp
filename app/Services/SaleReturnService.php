<?php

namespace App\Services;

use App\Repositories\SaleReturnRepository;

class SaleReturnService extends ReturnService
{
    public function __construct(SaleReturnRepository $returnRepo)
    {
        parent::__construct($returnRepo);
    }
}