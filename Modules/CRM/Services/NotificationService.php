<?php
namespace Modules\CRM\Services;

use Modules\Core\Services\ServiceContract;
use Modules\CRM\Entities\Notification;

class NotificationService extends ServiceContract {

    public function __construct(Notification $notification)
    {
        $this->model = $notification;
    }
    
}