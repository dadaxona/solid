<?php

namespace Modules\Telegram\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Telegram\Entities\Announcement;
use Modules\Telegram\Entities\JobApplication;
use Modules\Telegram\Entities\TelegramBot;
use Modules\Telegram\Entities\TelegramUser;
use Modules\Telegram\Services\TelegramBotService;
use Modules\Telegram\Services\TelegramService;

class TelegramController extends Controller
{
    
    public function __construct(protected TelegramService $service){ }
 
    public function setWebhook(TelegramBot $telegramBot)
    {
        $this->service->setTelegramBot($telegramBot);
        $this->service->setWebhook();
        return 'ok';
    }
    
    public function removeWebhook(TelegramBot $telegramBot)
    {
        
        $this->service->setTelegramBot($telegramBot);
        $this->service->removeWebhook();
        return 'ok';
    }
    public function getupdates(TelegramBot $telegramBot)
    {
        $this->service->setTelegramBot($telegramBot);

        $updates = $this->service->getUpdates();
        $update_count = count($updates);
        echo "there are {$update_count} \n ";
        foreach($updates as $update){
            $this->service->responseForMessage($update);
        }
    }
    public function webhookHandler($token)
    {
        $telegramBot = TelegramBot::where('token', $token)->first();
        $this->service->setTelegramBot($telegramBot);
        $this->service->responseForMessage(request()->all());

        return 'ok';
    }

    public function statistics()
    {
        $service = app()->make(TelegramBotService::class);
        $hrBot = $service->getByStrategy('hr-management');
        $annBot = $service->getByStrategy('announcement');


        // User data
        $hr_user = [
            'dates' => [],
            'data' => []
        ];
        $hr_users = TelegramUser::whereHas('telegram_chats', function($query) use ($hrBot){
            $query->where('telegram_bot_id', $hrBot->id);
        })->select(
            DB::raw('count(telegram_users.id) as data'),
            DB::raw("to_char(created_at, 'Mon-YYYY') date"),)
            ->groupby('date')
            ->get()->toArray();
        foreach ($hr_users as $value) {
            $hr_user['dates'][] = $value['date']; 
            $hr_user['data'][] = $value['data']; 
        }

        $ann_user = [
            'dates' => [],
            'data' => []
        ];
        $ann_users = TelegramUser::whereHas('telegram_chats', function($query) use ($annBot){
            $query->where('telegram_bot_id', $annBot->id);
        })->select(
            DB::raw('count(telegram_users.id) as data'),
            DB::raw("to_char(created_at, 'Mon-YYYY') date"),)
            ->groupby('date')
            ->get()->toArray();
        foreach ($ann_users as $value) {
            $ann_user['dates'][] = $value['date']; 
            $ann_user['data'][] = $value['data']; 
        }


        // Applications
        $applications = [
            'dates' => [],
            'data' => []
        ];
        $job_apps = JobApplication::select(
            DB::raw('count(id) as data'),
            DB::raw("to_char(created_at, 'Mon-YYYY') date"),)
            ->groupby('date')
            ->get()
            ->toArray();
        
        foreach ($job_apps as $value) {
            $applications['dates'][] = $value['date']; 
            $applications['data'][] = $value['data']; 
        }
        $announcements = [
            'dates' => [],
            'sell_data' => [],
            'buy_data' => []
        ];
        $ann_sell = Announcement::select(
            'type',
            DB::raw('count(announcements.id) as data'),
            DB::raw("to_char(announcements.created_at, 'Mon-YYYY') date"))
            ->where('type', 'announcements.sell')
            ->groupby('date', "type")
            ->get()
            ->toArray();
            
        $ann_buy = Announcement::select(
            'type',
            DB::raw('count(announcements.id) as data'),
            DB::raw("to_char(announcements.created_at, 'Mon-YYYY') date"))
            ->where('type', 'announcements.buy')
            ->groupby('date', "type")
            ->get()
            ->toArray();
            
        foreach($ann_sell as $items){
            $announcements['dates'][] = $items['date'];
            $announcements['sell_data'][] = $items['data'];
        }
        foreach($ann_buy as $items){
            $announcements['buy_data'][] = $items['data'];
        }
        return [
            'hr' => [
                'user' => $hr_user,
                'applications' => $applications
            ],
            'announcement' => [
                'user' => $ann_user,
                'announcements' => $announcements
            ]
        ];
    }
}