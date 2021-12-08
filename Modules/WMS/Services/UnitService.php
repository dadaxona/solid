<?php
namespace Modules\WMS\Services;

use Modules\Core\Services\ServiceContract;
use Modules\WMS\Entities\Unit;

class UnitService extends ServiceContract {

    public function __construct(Unit $unit)
    {
        $this->model = $unit;
    }
       
}