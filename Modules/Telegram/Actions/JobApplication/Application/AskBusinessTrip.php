<?php
namespace Modules\Telegram\Actions\JobApplication\Application;

use Modules\Telegram\Actions\Action;
use Modules\Telegram\Actions\JobApplication\MenuAction as JobApplicationMenuAction;
use Modules\Telegram\Entities\JobApplication;
use Telegram\Bot\Keyboard\Keyboard;
use Illuminate\Support\Str;

class AskBusinessTrip extends Action
{
    public static $ROUTE_NAME ='start.menu.ask-business-trip';
    public function getActions()
    {
        return [
            'cancel' => JobApplicationMenuAction::class,
           
        ];
    }
    public function getNextAction($message)
    {
        return (new AskMilitaryService($this->telegram ,$this->telegram_chat, $this->telegram_user))->makeView($message);
    }

    
    public function makeViewParams($message)
    {
        $inlineLayout = [
            [
                Keyboard::inlineButton(['text' => $this->translate('Ha'), 'callback_data' => $this::$ROUTE_NAME . '(yes)']),
                Keyboard::inlineButton(['text' => $this->translate('Yo\'q'), 'callback_data' => $this::$ROUTE_NAME . '(no)']),
            ],
        ];

        return [
            'chat_id' => $this->telegram_chat->chat_id, 
            'reply_markup' => json_encode([
                'inline_keyboard' => $inlineLayout,
            ]),
            'text' => $this->translate("Korxona tomonidan xizmat safariga chiqishga rozimisiz")
        ];
    }
    public function proccessMessageData($message)
    {
        $message_text = $this->getMessageText($message);
        $value = Str::between($message_text, '(', ')');
        $job_application = JobApplication::find($this->telegram_chat->job_application_id);
        if($job_application)
            $job_application->update(['business_trip' => $value]);
    }
}