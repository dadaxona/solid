<?php
namespace Modules\Telegram\Actions\Announcement\Registration;

use Modules\Telegram\Actions\Action;
use Modules\Telegram\Services\TelegramUserService;
use Illuminate\Support\Facades\Validator;

class NameAction extends Action
{
    public static $ROUTE_NAME ='registration.name';
    public function getNextAction($message)
    {
        return (new BirthDateAction($this->telegram, $this->telegram_chat, $this->telegram_user))->makeView($message);
    }
    public function makeViewParams($message)
    {
        return [
            'chat_id' => $message['message']['chat']['id'],
            'reply_markup' => json_encode([
                "remove_keyboard" => true
            ]),
            'text' => 'Iltimos, Ismingizni to\'liq kiriting:'
        ];
    }
    
    public function validateMessage($message)
    {
        $message_text = $this->getMessageText($message);
        $rules = ['message_text' => 'min:3'];

        $input = ['message_text' => $message_text];

        return Validator::make($input, $rules)->passes();
    }
    public function proccessMessageData($message)
    {
        $message_text = $this->getMessageText($message);
        $telegram_user = app()->make(TelegramUserService::class)->getOrCreate($this->telegram_chat->chat_id, Action::getUser($message));
        $telegram_user->update(['full_name' => $message_text]);
    }
}