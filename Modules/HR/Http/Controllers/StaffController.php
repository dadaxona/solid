<?php

namespace Modules\HR\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\HR\Http\Requests\StaffRequest;
use Modules\HR\Services\StaffService;

class StaffController extends Controller
{
    public function __construct(protected StaffService $service){
        $this->config = [
            'list' => [
                'columns' => ['id', 'came_at','work_book','study_degree','specialization','experience','staffposition_id','user_id'],
                'relations' => [ 'user', 'staffposition' ]
            ]
        ];
    }

    public function store(StaffRequest $request)
    {
        $staff = $this->service->create($request->validated());
        return response()->json(['item' => $staff]);
    }
    public function update(StaffRequest $request)
    {
        $user = $this->service->update($request->validated());
        return response()->json(['item' => $user]);
    }
}