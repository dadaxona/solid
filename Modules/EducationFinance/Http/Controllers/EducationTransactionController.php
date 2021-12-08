<?php

namespace Modules\EducationFinance\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\EducationFinance\Services\EducationTransactionService;
use Modules\EducationFinance\Http\Requests\EducationTransactionRequest;

class EducationTransactionController extends Controller
{
    
    public function __construct(protected EducationTransactionService $service){
        $this->config = [
            'list' => [
                'columns' => ['id', 'performed_at', 'group_id', 'student_id', 'amount', 'description'],
                'relations' => [ 'student.user', 'group']
            ]
        ];
    }
    
    public function store(EducationTransactionRequest $request)
    {
        $transaction = $this->service->create($request->validated());
        return response()->json(['item' => $transaction]);
    }
    public function update(EducationTransactionRequest $request)
    {
        $transaction = $this->service->update($request->validated());
        return response()->json(['item' => $transaction]);
    }

}