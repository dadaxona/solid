<?php
namespace Modules\Education\Services;

use Modules\User\Services\UserService;
use Modules\Core\Services\ServiceContract;
use Modules\Education\Entities\Student;

class StudentService extends ServiceContract {

    public function __construct(Student $student)
    {
        $this->model = $student;
    }

    public function create($data){
        $user = $data['user']??null;
        unset($data['user']);
        $user = app()->make(UserService::class)->create($user);

        $data['user_id'] = $user->id;
        $student = $this->model->create($data);
        
        return $student;
    }

    public function update($data)
    {
        $user_data = $data['user']??null;
        unset($data['user']);
        
        $student = $this->model->find($data['id']);
        $student->update($data);
        
        $user = app()->make(UserService::class)->update($user_data);
        
        return $user;
    }
}