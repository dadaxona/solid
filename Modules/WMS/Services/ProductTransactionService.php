<?php
namespace Modules\WMS\Services;

use Modules\Core\Services\ServiceContract;
use Modules\WMS\Entities\ProductTransaction;

class ProductTransactionService extends ServiceContract {

    public function __construct(ProductTransaction $porducttransaction)
    {
        $this->model = $porducttransaction;
    }

}