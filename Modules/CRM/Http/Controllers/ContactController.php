<?php

namespace Modules\CRM\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\CRM\Http\Requests\ContactRequest;
use Modules\CRM\Services\ContactService;

class ContactController extends Controller
{
    public function __construct(protected ContactService $service){}

    public function store(ContactRequest $request)
    {
        $contact = $this->service->create($request->all());
        return response()->json(['item' => $contact]);
    }
}