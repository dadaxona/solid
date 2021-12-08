<?php
namespace Modules\Telegram\Actions\JobApplication\Application;

use Modules\Telegram\Actions\Action;
use Modules\Telegram\Actions\JobApplication\MenuAction;
use Modules\Telegram\Entities\JobApplication;
use Telegram\Bot\Keyboard\Keyboard;

class AskRegion extends Action
{
    public static $ROUTE_NAME ='start.menu.ask-region';
    public function getActions()
    {
        return [
           "cancel" => MenuAction::class,
            'ask_region' => AskRegion::class
        ];
    }
    public function getNextAction($message)
    {
        return (new AskLiveTashkent($this->telegram, $this->telegram_chat, $this->telegram_user))->makeView($message);
    }

    
    public function makeViewParams($message)
    {
        $inlineLayout = [
            [
                Keyboard::inlineButton(['text' => $this->translate("Andijon v."), 'callback_data' => $this->translate("Andijon v.")]),
                Keyboard::inlineButton(['text' => $this->translate("Farg'ona v."), 'callback_data' => $this->translate("Farg'ona v.")]),
                Keyboard::inlineButton(['text' => $this->translate("Namangan v."), 'callback_data' => $this->translate("Namangan v.")]),
            ],
            [
                Keyboard::inlineButton(['text' => $this->translate("Sirdaryo v."), 'callback_data' => $this->translate("Sirdaryo v.")]),
                Keyboard::inlineButton(['text' => $this->translate("Jizzax v."), 'callback_data' => $this->translate("Jizzax v.")]),
                Keyboard::inlineButton(['text' => $this->translate("Samarqand v."), 'callback_data' => $this->translate("Samarqand v.")]),
            ],
            [
                Keyboard::inlineButton(['text' => $this->translate("Surxondaryo v."), 'callback_data' => $this->translate("Surxondaryo v.")]),
                Keyboard::inlineButton(['text' => $this->translate("Qashqadaryo v."), 'callback_data' => $this->translate("Qashqadaryo v.")]),
                Keyboard::inlineButton(['text' => $this->translate("Buxoro v."), 'callback_data' => $this->translate("Buxoro v.")]),
            ],
            [
                Keyboard::inlineButton(['text' => $this->translate("Navoiy v."), 'callback_data' => $this->translate("Navoiy v.")]),
                Keyboard::inlineButton(['text' => $this->translate("Xorazm v."), 'callback_data' => $this->translate("Xorazm v.")]),
                Keyboard::inlineButton(['text' => $this->translate("Qoraqalpogʻiston R."), 'callback_data' => $this->translate("Qoraqalpogʻiston R.")]),
            ],
            [
                Keyboard::inlineButton(['text' => $this->translate("Toshkent v."), 'callback_data' => $this->translate("Toshkent v.")]),
                Keyboard::inlineButton(['text' => $this->translate("Toshkent sh."), 'callback_data' => $this->translate("Toshkent sh.")]),
            ],
        ];

        return [
            'chat_id' => $this->telegram_chat->chat_id, 
            'reply_markup' => json_encode([
                'inline_keyboard' => $inlineLayout
            ]),
            'text' => $this->translate("Qaysi viloyatdansiz?")
        ];
    }
    public function proccessMessageData($message)
    {
        $message_text = $this->getMessageText($message);
        $job_application = JobApplication::find($this->telegram_chat->job_application_id);
        if($job_application)
            $job_application->update(['region' => $message_text]);
    }
}