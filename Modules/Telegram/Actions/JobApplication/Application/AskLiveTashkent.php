<?php
namespace Modules\Telegram\Actions\JobApplication\Application;

use Modules\Telegram\Actions\Action;
use Modules\Telegram\Actions\JobApplication\MenuAction;
use Modules\Telegram\Entities\JobApplication;
use Telegram\Bot\Keyboard\Keyboard;

class AskLiveTashkent extends Action
{
    public static $ROUTE_NAME ='start.menu.ask-live-tashkent';
    public function getActions()
    {
        return [
            "cancel" => MenuAction::class,
        ];
    }
    public function getNextAction($message)
    {
        return (new AskNationality($this->telegram, $this->telegram_chat, $this->telegram_user))->makeView($message);
    }

    
    public function makeViewParams($message)
    {
        // ikkalasi tarjima
        $inlineLayout = [
            [
                Keyboard::inlineButton(['text' => $this->translate("Ha"), 'callback_data' => $this->translate("Ha")]),
                Keyboard::inlineButton(['text' => $this->translate("Yo'q"), 'callback_data' => $this->translate("Yo'q")]),
            ],
        ];
        // faqat text
        return [
            'chat_id' => $this->telegram_chat->chat_id, 
            'reply_markup' => json_encode([
                'inline_keyboard' => $inlineLayout
            ]),
            'text' => $this->translate("Siz Toshkent shahri yashaysizmi?")
        ];
    }
    public function proccessMessageData($message)
    {
        $message_text = $this->getMessageText($message);
        $job_application = JobApplication::find($this->telegram_chat->job_application_id);
        if($job_application)
            $job_application->update(['live_in_tashkent' => $message_text]);
    }
}