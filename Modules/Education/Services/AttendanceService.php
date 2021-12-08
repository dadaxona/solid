<?php
namespace Modules\Education\Services;

use Modules\Core\Services\ServiceContract;
use Modules\Education\Entities\Attendance;

class AttendanceService extends ServiceContract {

    public function __construct(Attendance $attendance)
    {
        $this->model = $attendance;
    }
    
    public function create($data)
    {
        $student_ids = $data['student_ids'];
        unset($data['student_ids']);
        
        $attendance = $this->model->create($data);
        
        $attendance->students()->attach($student_ids);
        
        return $attendance;
    }
    
}