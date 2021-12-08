<?php

namespace Modules\Education\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Education\Http\Requests\GroupRequest;
use Modules\Education\Services\GroupService;

class GroupController extends Controller
{
    public function __construct(protected GroupService $service){
        $this->config = [
            'list' => [
                'columns' => ['id', 'name', 'created_at', 'room_id', 'course_id'],
                'relations' => [ 'room', 'course' ]
            ]
        ];
    }
    public function store(GroupRequest $request)
    {
        $group = $this->service->create($request->validated());
        return response()->json(['item' => $group]);
    }
    public function update(GroupRequest $request)
    {
        $group = $this->service->update($request->validated());
        return response()->json(['item' => $group]);
    }
}