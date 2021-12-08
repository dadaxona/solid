<?php

namespace Modules\Education\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Education\Http\Requests\TeacherRequest;
use Modules\Education\Services\TeacherService;

class TeacherController extends Controller
{
    

    public function __construct(protected TeacherService $service){
        $this->config = [
            'list' => [
                'columns' => ['id', 'created_at', 'user_id'],
                'relations' => [ 'user' ]
            ]
        ];
    }

    public function store(TeacherRequest $request)
    {
        $item = $this->service->create($request->validated());
        return response()->json(['item' => $item]);
    }
    public function update(TeacherRequest $request)
    {
        $item = $this->service->update($request->validated());
        return response()->json(['item' => $item]);
    }
    
    public function show($id)
    {
        $item = $this->service->get($id);

        $item->load('user');
        
        return response()->json(['item' => $item]);
    }
}