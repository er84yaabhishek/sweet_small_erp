<?php

namespace App\Services;

use App\Repositories\PurchaseReturnRepository;

class PurchaseReturnService extends ReturnService
{
    public function __construct(PurchaseReturnRepository $returnRepo)
    {
        parent::__construct($returnRepo);
    }
}
