<?php
namespace Modules\Telegram\Actions\JobApplication\Application;

use Modules\Telegram\Actions\Action;
use Modules\Telegram\Actions\JobApplication\MenuAction;
use Modules\Telegram\Entities\JobApplication;
use Telegram\Bot\Keyboard\Keyboard;

class AskFamilyPosition extends Action
{
    public static $ROUTE_NAME ='start.menu.ask-family-position';
    public function getActions()
    {
        return [
            "cancel" => MenuAction::class,
        ];
    }
    public function getNextAction($message)
    {
        return (new AskLastCompanyPosition($this->telegram, $this->telegram_chat, $this->telegram_user))->makeView($message);
    }

    
    public function makeViewParams($message)
    {
        // ikkalasi tarjima
        $inlineLayout = [
            [
                Keyboard::inlineButton(['text' => $this->translate("Turmush qurganman"), 'callback_data' => $this->translate("Turmush qurganman")
               ]),
            ],
            [
                Keyboard::inlineButton(['text' => $this->translate("Turmush qurmaganman"), 'callback_data' => $this->translate("Turmush qurmaganman")]),
            ],
        ];
        // text tarjima qilinadi
        return [
            'chat_id' => $this->telegram_chat->chat_id, 
            'reply_markup' => json_encode([
                'inline_keyboard' => $inlineLayout
            ]),
            'text' => $this->translate("Oilaviy xolatingiz")
        ];
    }
    public function proccessMessageData($message)
    {
        $message_text = $this->getMessageText($message);
        $job_application = JobApplication::find($this->telegram_chat->job_application_id);
        if($job_application)
            $job_application->update(['family_position' => $message_text]);
    }
}