<?php
namespace Modules\EducationFinance\Services;

use Modules\Core\Services\ServiceContract;
use Modules\EducationFinance\Entities\EducationTransaction;

class EducationTransactionService extends ServiceContract {

    public function __construct(EducationTransaction $transaction)
    {
        $this->model = $transaction;
    }
    
}