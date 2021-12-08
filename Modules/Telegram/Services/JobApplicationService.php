<?php
namespace Modules\Telegram\Services;

use Modules\Core\Services\ServiceContract;
use Modules\Telegram\Entities\JobApplication;

class JobApplicationService extends ServiceContract
{
    public function __construct(JobApplication $model)
    {
        $this->model = $model;
    }
}