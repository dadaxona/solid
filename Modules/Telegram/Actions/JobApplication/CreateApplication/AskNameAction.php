<?php
namespace Modules\Telegram\Actions\JobApplication\CreateApplication;

use Modules\Telegram\Actions\Action;
use Modules\Telegram\Actions\JobApplication\MenuAction;
use Modules\Telegram\Entities\JobApplication;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Telegram\Bot\Keyboard\Keyboard;

class AskNameAction extends Action
{    
    public static $ROUTE_NAME ='start.menu.ask-name';
    public function getActions()
    {
        return [
            "back" => MenuAction::class,
        ];
    }
    public function getNextAction($message)
    {
        return (new AskBirthDateAction($this->telegram, $this->telegram_chat, $this->telegram_user))->makeView($message);
    }
    public function makeViewParams($message)
    {
         $inlineLayout = [
            [
                Keyboard::inlineButton(['text' => $this->translate(" ◀️ Orqaga"), 'callback_data' => self::$ROUTE_NAME . ":back"]),
            ],
        ];

    return [
        'chat_id' => $this->telegram_chat->chat_id,
        'parse_mode' => 'HTML',
        'reply_markup' => json_encode([
            'inline_keyboard' => $inlineLayout,
        ]),
            'text' => $this->translate("Ism,familiyangizni to‘liq yozing\n(Axmadjon Axmedov)")
        ];
    }
    public function validateMessage($message)
    {
        Log::info($message);
        $message_text = $this->getMessageText($message);
        $rules = ['message_text' => 'string|min:5|max:150'];

        $input = ['message_text' => $message_text];

        return Validator::make($input, $rules)->passes();
    }
    public function proccessMessageData($message)
    {
        $message_text = $this->getMessageText($message);
        $job_application = JobApplication::create(['full_name' => $message_text]);
        $this->telegram_chat->update(['job_application_id' => $job_application->id]);
    }
}