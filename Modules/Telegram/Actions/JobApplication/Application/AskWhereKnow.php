<?php
namespace Modules\Telegram\Actions\JobApplication\Application;

use Modules\Telegram\Actions\Action;
use Modules\Telegram\Actions\JobApplication\MenuAction;
use Modules\Telegram\Entities\JobApplication;
use Telegram\Bot\Keyboard\Keyboard;

class AskWhereKnow extends Action
{
    public static $ROUTE_NAME ='start.menu.ask-where-know';
    public function getActions()
    {
        return [
            "cancel" => MenuAction::class,
        ];
    }
    public function getNextAction($message)
    {
        return (new AskPhoto($this->telegram, $this->telegram_chat, $this->telegram_user))->makeView($message);
    }

    
    public function makeViewParams($message)
    {
        $inlineLayout = [
            [
                Keyboard::inlineButton(['text' => $this->translate("Telegram"), 'callback_data' => $this->translate("Telegram")]),
            ],
            [
                Keyboard::inlineButton(['text' => $this->translate("Instagram"), 'callback_data' => $this->translate("Instagram")]),
            ],
            [
                Keyboard::inlineButton(['text' => $this->translate("Facebook"), 'callback_data' => $this->translate("Facebook")]),
            ],
            [
                Keyboard::inlineButton(['text' => $this->translate("TikTok"), 'callback_data' => $this->translate("TikTok")]),
            ],
            [
                Keyboard::inlineButton(['text' => $this->translate("Tanishimdan"), 'callback_data' => $this->translate("Tanishimdan")]),
            ],
        ];

        return [
            'chat_id' => $this->telegram_chat->chat_id, 
            'reply_markup' => json_encode([
                'inline_keyboard' => $inlineLayout,
            ]),
            'text' => $this->translate("Korxonamiz xaqida qayerdan maʼlumot oldingiz")
        ];
    }
    public function proccessMessageData($message)
    {
        $message_text = $this->getMessageText($message);
        $job_application = JobApplication::find($this->telegram_chat->job_application_id);
        if($job_application)
            $job_application->update(['where_know' => $message_text]);
    }
}