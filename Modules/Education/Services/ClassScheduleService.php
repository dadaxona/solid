<?php
namespace Modules\Education\Services;

use Modules\Core\Services\ServiceContract;
use Modules\Education\Entities\ClassSchedule;

class ClassScheduleService extends ServiceContract {

    public function __construct(ClassSchedule $classschedule)
    {
        $this->model = $classschedule;
    }
    
}