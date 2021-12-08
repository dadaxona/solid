<?php

namespace Modules\Education\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Education\Http\Requests\ClassScheduleRequest;
use Modules\Education\Services\ClassScheduleService;

class ClassScheduleController extends Controller
{
    public function __construct(protected ClassScheduleService $service){
        $this->config = [
            'list' => [
                'columns' => [
                    'id',
                    'created_at',
                    'group_id',
                    'room_id',
                    'monday',
                    'monday_from',
                    'monday_to',
                    'tuesday',
                    'tuesday_from',
                    'tuesday_to',
                    'wednesday',
                    'wednesday_from',
                    'wednesday_to',
                    'thursday',
                    'thursday_from',
                    'thursday_to',
                    'friday',
                    'friday_from',
                    'friday_to',
                    'saturday',
                    'saturday_from',
                    'saturday_to',
                    'sunday',
                    'sunday_from',
                    'sunday_to',
                ],
                'relations' => [ 'group', 'room' ]
            ]
        ];
    }

    public function store(ClassScheduleRequest $request)
    {
        $classschedule = $this->service->create($request->all());
        return response()->json(['item' => $classschedule]);
    }
    public function update(ClassScheduleRequest $request)
    {
        $classschedule = $this->service->update($request->all());
        return response()->json(['item' => $classschedule]);
    }
}