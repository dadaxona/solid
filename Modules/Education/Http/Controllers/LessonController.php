<?php

namespace Modules\Education\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Education\Http\Requests\LessonRequest;
use Modules\Education\Services\LessonService;

class LessonController extends Controller
{
    public function __construct(protected LessonService $service){
        $this->config = [
            'list' => [
                'columns' => ['id', 'name', 'created_at', 'duration', 'course_id'],
                'relations' => [ 'course' ]
            ]
        ];
    }

    public function store(LessonRequest $request)
    {
        $lesson = $this->service->create($request->validated());
        return response()->json(['item' => $lesson]);
    }
    public function update(LessonRequest $request)
    {
        $user = $this->service->update($request->validated());
        return response()->json(['item' => $user]);
    }
}