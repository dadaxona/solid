<?php

namespace Modules\Education\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Education\Http\Requests\CertificateRequest;
use Modules\Education\Services\CertificateService;

class CertificateController extends Controller
{
    

    public function __construct(protected CertificateService $service){
        $this->config = [
            // 'list' => [
            //     'columns' => ['id', 'created_at', 'user_id'],
            //     'relations' => [ 'user' ]
            // ]
        ];
    }

    public function store(CertificateRequest $request)
    {
        $certificate = $this->service->create($request->validated());
        return response()->json(['item' => $certificate]);
    }
    public function update(CertificateRequest $request)
    {
        $certificate = $this->service->update($request->validated());
        return response()->json(['item' => $certificate]);
    }
    public function loadDataFromFile()
    {
        return $this->service->loadDataFromFile();
    }
    public function generateFile($id)
    {
        return $this->service->generateFile($id);
    }
}