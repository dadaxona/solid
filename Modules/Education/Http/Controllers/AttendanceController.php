<?php

namespace Modules\Education\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Education\Http\Requests\AttendanceRequest;
use Modules\Education\Services\AttendanceService;

class AttendanceController extends Controller
{
    public function __construct(protected AttendanceService $service){
        $this->config = [
            'list' => [
                'columns' => ['id', 'created_at', 'group_id', 'date_for'],
                'relations' => [ 'group' ]
            ]
        ];
    }

    public function store(AttendanceRequest $request)
    {
        $attendance = $this->service->create($request->validated());
        return response()->json(['item' => $attendance]);
    }
    public function update(AttendanceRequest $request)
    {
        $attendance = $this->service->update($request->validated());
        return response()->json(['item' => $attendance]);
    }
}