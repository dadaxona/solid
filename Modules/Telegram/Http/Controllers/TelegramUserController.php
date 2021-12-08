<?php

namespace Modules\Telegram\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Telegram\Entities\TelegramUser;
use Modules\Telegram\Http\Requests\TelegramUserRequest;
use Modules\Telegram\Services\TelegramUserService;
use SDpro\PhpExcelTemplator\params\CallbackParam;
use SDpro\PhpExcelTemplator\PhpExcelTemplator;

class TelegramUserController extends Controller
{
    public function __construct(protected TelegramUserService $service){ }

    public function store(TelegramUserRequest $request)
    {
        $user = $this->service->create($request->validated());
        return response()->json(['item' => $user]);
    }
    public function update(TelegramUserRequest $request)
    {
        $user = $this->service->update($request->validated());
        return response()->json(['item' => $user]);
    }
    public function exportFile($file_name){
        $telegeram_users = TelegramUser::with('region', 'direction')->orderBy('created_at', 'desc')->get();
        $templateFile = storage_path('export-templates/telegram_users.xlsx');

        $params = [
            '{datetime}' => now(),
            '{total}' => $telegeram_users->count(),
            '[id]' => $telegeram_users->pluck('id')->toArray(),
            '[telegramId]' => $telegeram_users->pluck('user_id')->toArray(),
            '[name]' => $telegeram_users->pluck('name')->toArray(),
            '[region]' => $telegeram_users->pluck('region.name')->toArray(),
            '[direction]' => $telegeram_users->pluck('direction.name')->toArray(),
            '[created_at]' => $telegeram_users->pluck('created_at')->toArray(),
            '[registred]' => $telegeram_users->pluck('registration_status')->map(function ($item, $key) {
                switch($item){
                    case 'pending':
                        return 'Kutilmoqda';
                    case 'completed':
                        return 'Yakunlandi';
                    default:
                        return 'Nomalum';
                }
            })->toArray()
        ];
        $callbacks = [
            '[registred]' => function(CallbackParam $param) {
                $status = $param->param[$param->row_index];
                if ($status == 'Yakunlandi') {
                    $cell_coordinate = $param->coordinate;
                    $param->sheet->getStyle($cell_coordinate)->getFont()->setBold(true);
                }
            },
        ];

        return PhpExcelTemplator::outputToFile($templateFile, $file_name, $params, $callbacks);
    }
}