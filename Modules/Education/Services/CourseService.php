<?php
namespace Modules\Education\Services;

use Modules\Core\Services\ServiceContract;
use Modules\Education\Entities\Course;
use Modules\Education\Entities\Lesson;

class CourseService extends ServiceContract {

    public function __construct(Course $course)
    {
        $this->model = $course;
    }
    
    public function create($data)
    {
        $model = $this->model->create($data);
        if(isset($data['lesson_ids'])){
            Lesson::whereIn('id', $data['lesson_ids'])->update(['course_id'=>$model->id]);
        }
        return $model;
    }
    
    public function update($data)
    {
        $model = $this->model->find($data['id']);
        $model->update($data);
        if(isset($data['lesson_ids'])){
            Lesson::whereIn('id', $data['lesson_ids'])->update(['course_id'=>$model->id]);
        }
        return $model;
    }
    
}