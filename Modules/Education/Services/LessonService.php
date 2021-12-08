<?php
namespace Modules\Education\Services;

use Modules\Core\Services\ServiceContract;
use Modules\Education\Entities\Lesson;

class LessonService extends ServiceContract {

    public function __construct(Lesson $lesson)
    {
        $this->model = $lesson;
    }
    
}