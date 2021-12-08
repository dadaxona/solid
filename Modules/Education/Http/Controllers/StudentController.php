<?php

namespace Modules\Education\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Education\Services\StudentService;
use Modules\Education\Http\Requests\StudentRequest;

class StudentController extends Controller
{
    public function __construct(protected StudentService $service){
        $this->config = [
            'list' => [
                'columns' => ['id', 'created_at', 'finished_at', 'user_id'],
                'relations' => [ 'user' ]
            ]
        ];
    }
    public function store(StudentRequest $request)
    {
        $student = $this->service->create($request->all());
        return response()->json(['item' => $student]);
    }
    public function update(StudentRequest $request)
    {
        $user = $this->service->update($request->validated());
        return response()->json(['item' => $user]);
    }
}