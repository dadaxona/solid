<?php
namespace Modules\Telegram\Actions\JobApplication\CreateApplication;

use Modules\Telegram\Actions\Action;
use Modules\Telegram\Actions\JobApplication\Application\ChooseDepartmentAction;
use Modules\Telegram\Actions\JobApplication\MenuAction;
use Modules\Telegram\Entities\JobApplication;
use Illuminate\Support\Facades\Validator;
use Telegram\Bot\Keyboard\Keyboard;

class AskPhoneAction extends Action
{
    public static $ROUTE_NAME ='start.menu.ask-phone';
    public function getActions()
    {
        return [
            "cancel" => MenuAction::class,
        ];
    }
    public function getNextAction($message)
    {
        return (new ChooseDepartmentAction($this->telegram, $this->telegram_chat, $this->telegram_user))->makeView($message);
    }
    public function makeViewParams($message)
    {
        $inlineLayout = [
            [
                Keyboard::inlineButton(['text' => $this->translate("Bekor qilish"), 'callback_data' => self::$ROUTE_NAME . ":cancel"]),
            ],
        ];

        return [
            'chat_id' => $this->telegram_chat->chat_id,
            'parse_mode' => 'HTML',
            'reply_markup' => json_encode([
                'inline_keyboard' => $inlineLayout,
        ]),
            'text' =>  $this->translate("Telefon raqamingiz\n(+998916830071)")
        ];
    }
    
    public function validateMessage($message)
    {
        $message_text = $this->getMessageText($message);
        $rules = ['message_text' => 'string|min:7|max:15'];

        $input = ['message_text' => $message_text];

        return Validator::make($input, $rules)->passes();
    }
    public function proccessMessageData($message)
    {
        $message_text = $this->getMessageText($message);
        $job_application = JobApplication::find($this->telegram_chat->job_application_id);
        if($job_application)
            $job_application->update(['phone_number' => $message_text]);
    }
}