<?php
namespace Modules\Telegram\Services;

use Modules\Core\Services\ServiceContract;
use Modules\Telegram\Entities\TelegramUser;

class TelegramUserService extends ServiceContract
{

    public function __construct(protected TelegramUser $telegram_user)
    {
        $this->model = $telegram_user;
    }
    
    
    public function getOrCreate($chat_id, $user)
    {
        $telegram_user = $this->model->where(['user_id' => $user['id']])->first();
        if(!$telegram_user){
            $telegram_user = $this->create([
                'user_id' => $user['id'],
                'name' => $user['first_name']??'',
                'second_name' => $user['last_name']??'',
            ]);
        }
        return $telegram_user;
    }
}