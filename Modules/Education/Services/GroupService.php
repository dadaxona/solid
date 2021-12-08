<?php
namespace Modules\Education\Services;

use Modules\Core\Services\ServiceContract;
use Modules\Education\Entities\Group;

class GroupService extends ServiceContract {

    public function __construct(Group $group)
    {
        $this->model = $group;
    }
    
    public function create($data){
        $students = $data['student_ids'];
        unset($data['student_ids']);

        $group = $this->model->create($data);
        $group->students()->attach($students);
        
        return $group;
    }

    public function update($data)
    {        
        $students = $data['student_ids'];
        unset($data['student_ids']);

        $group = $this->model->find($data['id']);
        $group->update($data);
        $group->students()->sync($students);
        
        return $group;
    }
}