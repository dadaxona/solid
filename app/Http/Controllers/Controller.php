<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected $config;
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index()
    {
        $items = $this->service->getList($this->config['list']??null);
        return response()->json(['items' => $items]);
    }

    public function show($id)
    {
        $item = $this->service->get($id);
        
        return response()->json(['item' => $item]);
    }

    public function destroy($id)
    {
        $item = $this->service->get($id);
        $item->delete();
        return response()->json(['success' => true]);   
    }

}