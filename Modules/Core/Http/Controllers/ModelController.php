<?php

namespace Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Core\Http\Requests\ModelRequest;
use Modules\Core\Services\ModelService;

class ModelController extends Controller
{
    public function __construct(protected ModelService $service){}

    public function attach(ModelRequest $request)
    {
        $this->service->attach($request->validated());
        return response()->json(['result' => true]);
    }
    public function dettach(ModelRequest $request)
    {
        $this->service->dettach($request->validated());
        return response()->json(['result' => true]);
    }
}