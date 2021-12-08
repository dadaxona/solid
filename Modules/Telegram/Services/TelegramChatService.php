<?php
namespace Modules\Telegram\Services;

use Modules\Core\Services\ServiceContract;
use Modules\Telegram\Entities\TelegramChat;

class TelegramChatService extends ServiceContract
{

    public function __construct(TelegramChat $telegram_chat)
    {
        $this->model = $telegram_chat;
    }
    
    public function create($data){
        $telegram_chat = $this->model->create($data);
        return $telegram_chat;
    }

    public function update($data)
    {
        $telegram_chat = $this->model->find($data['id']);
        $telegram_chat->update($data);
        return $telegram_chat;
    }
    public function getOrCreate($chat_id, $telegram_bot_id)
    {
        $telegram_chat = $this->model->where(['chat_id' => $chat_id, 'telegram_bot_id' => $telegram_bot_id])->first();
        if(!$telegram_chat){
            $telegram_chat = $this->create(['chat_id' => $chat_id, 'telegram_bot_id' => $telegram_bot_id, 'state' => '']);
        }
        return $telegram_chat;
    }
    public function getPrivateAdminChats()
    {
        return $this->model->where('chat_id', '>', 0)->whereHas('telegram_users', function($query){
            return $query->where('telegram_users.is_admin', true);
        })->get();
    }
}