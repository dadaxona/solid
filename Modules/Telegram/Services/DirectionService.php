<?php
namespace Modules\Telegram\Services;

use Modules\Core\Services\ServiceContract;
use Modules\Telegram\Entities\Direction;

class DirectionService extends ServiceContract
{

    public function __construct(Direction $direction)
    {
        $this->model = $direction;
    }
    
}