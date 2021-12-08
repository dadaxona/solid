<?php
namespace Modules\Telegram\Services;

use Modules\Core\Services\ServiceContract;
use Modules\Telegram\Entities\Announcement;

class AnnouncementService extends ServiceContract
{
    public function __construct(Announcement $model)
    {
        $this->model = $model;
    }
}