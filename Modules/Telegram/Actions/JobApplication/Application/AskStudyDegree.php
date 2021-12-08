<?php
namespace Modules\Telegram\Actions\JobApplication\Application;

use Modules\Telegram\Actions\Action;
use Modules\Telegram\Actions\JobApplication\MenuAction;
use Modules\Telegram\Entities\JobApplication;
use Telegram\Bot\Keyboard\Keyboard;

class AskStudyDegree extends Action
{
    public static $ROUTE_NAME ='start.menu.ask-study-degree';
    public function getActions()
    {
        return [
            "cancel" => MenuAction::class,
        ];
    }
    public function getNextAction($message)
    {
        return (new AskFamilyPosition($this->telegram, $this->telegram_chat, $this->telegram_user))->makeView($message);
    }

    
    public function makeViewParams($message)
    {
        $inlineLayout = [
            [
                Keyboard::inlineButton(['text' => $this->translate("O'rta"), 'callback_data' => $this->translate("O'rta")]),
            ],
            [    
                Keyboard::inlineButton(['text' => $this->translate("O'rta maxsus"), 'callback_data' => $this->translate("O'rta maxsus")]),
            ],
            [ 
                Keyboard::inlineButton(['text' => $this->translate("Oliy\\Bakalavr"), 'callback_data' => $this->translate("Oliy\\Bakalavr")]),
            ],
            [   
                Keyboard::inlineButton(['text' => $this->translate("Oliy\\Magistr"), 'callback_data' => $this->translate("Oliy\\Magistr")]),
            ],
        ];

        return [
            'chat_id' => $this->telegram_chat->chat_id, 
            'reply_markup' => json_encode([
                'inline_keyboard' => $inlineLayout,
            ]),
            'text' => $this->translate("Maʼlumotingiz")
        ];
    }
    
    public function proccessMessageData($message)
    {
        $message_text = $this->getMessageText($message);
        $job_application = JobApplication::find($this->telegram_chat->job_application_id);
        if($job_application)
            $job_application->update(['study_degree' => $message_text]);
    }
}