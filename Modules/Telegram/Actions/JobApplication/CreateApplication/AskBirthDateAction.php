<?php
namespace Modules\Telegram\Actions\JobApplication\CreateApplication;

use Modules\Telegram\Actions\Action;
use Modules\Telegram\Actions\JobApplication\MenuAction;
use Modules\Telegram\Entities\JobApplication;
use Illuminate\Support\Facades\Validator;
use Telegram\Bot\Keyboard\Keyboard;

class AskBirthDateAction extends Action
{
    public static $ROUTE_NAME ='start.menu.ask-birth-date';
    public function getActions()
    {
        return [
            "back" => MenuAction::class,
        ];
    }
    public function getNextAction($message)
    {
        return (new AskPhoneAction($this->telegram, $this->telegram_chat, $this->telegram_user))->makeView($message);
    }
    public function makeViewParams($message)
    {
        $inlineLayout = [
            [
                Keyboard::inlineButton(['text' => $this->translate(" ◀️ Orqaga"), 'callback_data' => self::$ROUTE_NAME . ":back"]),
            ],
        ];

        return [
            'chat_id' => $this->telegram_chat->chat_id,
            'parse_mode' => 'HTML',
            'reply_markup' => json_encode([
                'inline_keyboard' => $inlineLayout,
            ]),
            'text' => $this->translate("Tug‘ilgan kun, oy, yilingiz\n(24.03.1998)")
        ];
    }

    public function validateMessage($message)
    {
        $message_text = $this->getMessageText($message);
        $rules = ['message_text' => 'date_format:d.m.Y'];

        $input = ['message_text' => $message_text];

        return Validator::make($input, $rules)->passes();
    }
    public function proccessMessageData($message)
    {
        $message_text = $this->getMessageText($message);
        $job_application = JobApplication::find($this->telegram_chat->job_application_id);
        if($job_application)
            $job_application->update(['birth_date' => $message_text]);
    }
}