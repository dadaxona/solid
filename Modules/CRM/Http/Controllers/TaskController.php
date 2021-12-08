<?php

namespace Modules\CRM\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\CRM\Http\Requests\TaskRequest;
use Modules\CRM\Services\TaskService;

class TaskController extends Controller
{
    public function __construct(protected TaskService $service){}

    public function store(TaskRequest $request)
    {
        $task = $this->service->create($request->all());
        return response()->json(['item' => $task]);
    }

    public function update(TaskRequest $request)
    {
        $task = $this->service->update($request->all());
        return response()->json(['item' => $task]);
    }
     
}