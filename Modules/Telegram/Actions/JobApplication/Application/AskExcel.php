<?php
namespace Modules\Telegram\Actions\JobApplication\Application;

use Modules\Telegram\Actions\Action;
use Modules\Telegram\Actions\JobApplication\MenuAction;
use Modules\Telegram\Entities\JobApplication;
use Telegram\Bot\Keyboard\Keyboard;
use Illuminate\Support\Str;

class AskExcel extends Action
{
    public static $ROUTE_NAME ='start.menu.ask-excel';
    public function getActions()
    {
        return [
            "cancel" => MenuAction::class,
        ];
    }
    public function getNextAction($message)
    {
        return (new Ask1C($this->telegram, $this->telegram_chat, $this->telegram_user))->makeView($message);
    }

    
    public function makeViewParams($message)
    {
        $inlineLayout = [
            [
                Keyboard::inlineButton(['text' => '0 %', 'callback_data' => $this::$ROUTE_NAME . '(0)']),
            ],
            [
                Keyboard::inlineButton(['text' => '25 %', 'callback_data' => $this::$ROUTE_NAME . '(25)']),
            ],
            [
                Keyboard::inlineButton(['text' => '50 %', 'callback_data' => $this::$ROUTE_NAME . '(50)']),
            ],
            [
                Keyboard::inlineButton(['text' => '75 %', 'callback_data' => $this::$ROUTE_NAME . '(75)']),
            ],
            [
                Keyboard::inlineButton(['text' => '100 %', 'callback_data' => $this::$ROUTE_NAME . '(100)']),
            ],
        ];
        // text
        return [
            'chat_id' => $this->telegram_chat->chat_id, 
            'reply_markup' => json_encode([
                'inline_keyboard' => $inlineLayout
            ]),
            'text' =>$this->translate ("Excel dasturini bilishingiz darajasi\n(% ko‘rsating)")
        ];
    }
    public function proccessMessageData($message)
    {
        $message_text = $this->getMessageText($message);
        $value = Str::between($message_text, '(', ')');
        $job_application = JobApplication::find($this->telegram_chat->job_application_id);
        if($job_application)
            $job_application->update(['excel_level' => $value]);
    }
}