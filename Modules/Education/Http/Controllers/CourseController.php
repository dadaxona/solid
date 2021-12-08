<?php

namespace Modules\Education\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Education\Http\Requests\CourseRequest;
use Modules\Education\Services\CourseService;

class CourseController extends Controller
{
    public function __construct(protected CourseService $service){
        $this->config = [
            'list' => [
                'columns' => ['id', 'name', 'created_at', 'teacher_id', 'price'],
                'relations' => [ 'teacher.user' ]
            ]
        ];
    }


    public function store(CourseRequest $request)
    {
        $course = $this->service->create($request->validated());
        return response()->json(['item' => $course]);
    }
    public function update(CourseRequest $request)
    {
        $user = $this->service->update($request->validated());
        return response()->json(['item' => $user]);
    }
}