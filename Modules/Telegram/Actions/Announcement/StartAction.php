<?php
namespace Modules\Telegram\Actions\Announcement;

use Modules\Telegram\Actions\Action;
use Modules\Telegram\Actions\Announcement\Registration\NameAction;
use Modules\Telegram\Services\TelegramUserService;

class StartAction extends Action
{
    public static $ROUTE_NAME ='start';
    
    public function getActions()
    {
        return [ '/start' => self::class ];
    }
    
    public function getNextAction($message)
    {
        return (new MenuAction($this->telegram, $this->telegram_chat, $this->telegram_user))->makeView($message);
    }
    
    public function makeView($message)
    {
        $need_register = false;

        $telegram_user = app()->make(TelegramUserService::class)->getOrCreate($this->telegram_chat->chat_id, Action::getUser($message));
        
        if($telegram_user->registration_status == 'pending'){
            $need_register = true;
        }
        
        if($need_register){
            return (new NameAction($this->telegram, $this->telegram_chat, $this->telegram_user))->makeView($message);
        }else{
            return (new MenuAction($this->telegram, $this->telegram_chat, $this->telegram_user))->makeView($message);
        }
        $this->telegram->sendMessage($this->makeViewParams($message));
    }
    public function makeViewParams($message)
    {
        $text = 'Veroning e`lonlar botiga hush kelibsiz!';
        
        $this->telegram->sendMessage([
            'chat_id' => $this->telegram_chat->chat_id, 
            'text' => $text
        ]);
    }
    
    public function proccessMessageData($message)
    {
        $message_text = $this->getMessageText($message);
    }
}