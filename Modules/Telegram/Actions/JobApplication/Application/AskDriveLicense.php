<?php
namespace Modules\Telegram\Actions\JobApplication\Application;

use Modules\Telegram\Actions\Action;
use Modules\Telegram\Actions\JobApplication\MenuAction as JobApplicationMenuAction;
use Modules\Telegram\Entities\JobApplication;
use Telegram\Bot\Keyboard\Keyboard;
use Illuminate\Support\Str;

class AskDriveLicense extends Action
{
    public static $ROUTE_NAME ='start.menu.ask-driver-license';
    public function getActions()
    {
        return [
            "cancel" => JobApplicationMenuAction::class,
        ];
    }
    public function getNextAction($message)
    {
        return (new AskHasAuto($this->telegram, $this->telegram_chat, $this->telegram_user))->makeView($message);
    }

    
    public function makeViewParams($message)
    {
        $inlineLayout = [
            [
                Keyboard::inlineButton(['text' => 'B', 'callback_data' => $this::$ROUTE_NAME . '(B)']),
            ],
            [
                Keyboard::inlineButton(['text' => 'B, C', 'callback_data' => $this::$ROUTE_NAME . '(B, C)']),
            ],
            [
                Keyboard::inlineButton(['text' => $this->translate("Yo'q"), 'callback_data' => $this::$ROUTE_NAME . '(no)']),
            ],
            [
                Keyboard::inlineButton(['text' => $this->translate("Boshqa"), 'callback_data' =>  $this::$ROUTE_NAME . '(other)']),
            ],
        ];
        // test tarjima
        return [
            'chat_id' => $this->telegram_chat->chat_id, 
            'reply_markup' => json_encode([
                'inline_keyboard' => $inlineLayout,
            ]),
            'text' => $this->translate("Sizda qaysi turdagi haydovchilik guvohnomasi mavjud?")
        ];
    }
    public function proccessMessageData($message)
    {
        $message_text = $this->getMessageText($message);
        $value = Str::between($message_text, '(', ')');
        $job_application = JobApplication::find($this->telegram_chat->job_application_id);
        if($job_application)
            $job_application->update(['driver_license' => $value]);
    }
}