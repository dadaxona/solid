<?php
namespace Modules\CRM\Services;

use Modules\Core\Services\ServiceContract;
use Modules\CRM\Entities\Expense;

class ExpenseService extends ServiceContract {

    public function __construct(Expense $expense)
    {
        $this->model = $expense;
    }
    
}