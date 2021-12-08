<?php
namespace Modules\HR\Services;

use Modules\Core\Services\ServiceContract;
use Modules\HR\Entities\StaffPosition;

class StaffPositionService extends ServiceContract {

    public function __construct(StaffPosition $staffposition)
    {
        $this->model = $staffposition;
    }
    
}