<?php
namespace Modules\CRM\Services;

use Modules\Core\Services\ServiceContract;
use Modules\CRM\Entities\Task;

class TaskService extends ServiceContract {

    public function __construct(Task $task)
    {
        $this->model = $task;
    }
    
}