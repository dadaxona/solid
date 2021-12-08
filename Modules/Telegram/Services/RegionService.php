<?php
namespace Modules\Telegram\Services;

use Modules\Core\Services\ServiceContract;
use Modules\Telegram\Entities\Region;

class RegionService extends ServiceContract
{

    public function __construct(Region $region)
    {
        $this->model = $region;
    }
    
}