<?php
namespace Modules\Telegram\Actions\JobApplication\Application;

use Modules\Telegram\Actions\Action;
use Modules\Telegram\Actions\JobApplication\MenuAction;
use Modules\Telegram\Entities\JobApplication;
use Telegram\Bot\Keyboard\Keyboard;

class AskNationality extends Action
{
    public static $ROUTE_NAME ='start.menu.ask-nationality';
    public function getActions()
    {
        return [
            "cancel" => MenuAction::class,
        ];
    }
    public function getNextAction($message)
    {
        return (new AskStudyDegree($this->telegram, $this->telegram_chat, $this->telegram_user))->makeView($message);
    }

    
    public function makeViewParams($message)
    {
        $inlineLayout = [
            [
                Keyboard::inlineButton(['text' => $this->translate("O'zbek"), 'callback_data' => $this->translate("O'zbek")]),
            ],
            [    
                Keyboard::inlineButton(['text' => $this->translate("Rus"), 'callback_data' => $this->translate("Rus")]),
            ],
            [
                Keyboard::inlineButton(['text' => $this->translate("Boshqa"), 'callback_data' => $this->translate("Boshqa")]),
            ],
        ];

        return [
            'chat_id' => $this->telegram_chat->chat_id, 
            'reply_markup' => json_encode([
                'inline_keyboard' => $inlineLayout
            ]),
            'text' => $this->translate("Millatingiz?")
        ];
    }
    public function proccessMessageData($message)
    {
        $message_text = $this->getMessageText($message);
        $job_application = JobApplication::find($this->telegram_chat->job_application_id);
        if($job_application)
            $job_application->update(['nationality' => $message_text]);
    }
}