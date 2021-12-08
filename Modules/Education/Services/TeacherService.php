<?php
namespace Modules\Education\Services;

use Modules\Core\Services\ServiceContract;
use Modules\Education\Entities\Teacher;
use Modules\User\Services\UserService;

class TeacherService extends ServiceContract {

    public function __construct(Teacher $teacher)
    {
        $this->model = $teacher;
    }
    public function create($data){
        $user = $data['user']??null;
        unset($data['user']);
        
        $user = app()->make(UserService::class)->create($user);

        $data['user_id'] = $user->id;
        $teacher = $this->model->create($data);
        
        return $teacher;
    }

    public function update($data)
    {
        $user = $data['user']??null;
        unset($data['user']);
        if(isset($user['id'])){
            $user = app()->make(UserService::class)->update($user);
        }else{
            $user = app()->make(UserService::class)->create($user);
            $data['user_id'] = $user->id;
        }
        $teacher = $this->model->find($data['id']);
        $teacher->update($data);

        return $user;
    }
}