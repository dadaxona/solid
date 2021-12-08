<?php

namespace Modules\Education\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Education\Http\Requests\RoomRequest;
use Modules\Education\Services\RoomService;

class RoomController extends Controller
{
    public function __construct(protected RoomService $service){}

    public function store(RoomRequest $request)
    {
        $room = $this->service->create($request->validated());
        return response()->json(['item' => $room]);
    }
    public function update(RoomRequest $request)
    {
        $room = $this->service->update($request->validated());
        return response()->json(['item' => $room]);
    }
}