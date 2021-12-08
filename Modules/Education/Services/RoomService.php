<?php
namespace Modules\Education\Services;

use Modules\Core\Services\ServiceContract;
use Modules\Education\Entities\Room;

class RoomService extends ServiceContract {

    public function __construct(Room $room)
    {
        $this->model = $room;
    }
    
}