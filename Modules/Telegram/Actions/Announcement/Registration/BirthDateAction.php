<?php
namespace Modules\Telegram\Actions\Announcement\Registration;

use Modules\Telegram\Actions\Action;
use Modules\Telegram\Services\TelegramUserService;
use Illuminate\Support\Facades\Validator;
use Telegram\Bot\Keyboard\Keyboard;


class BirthDateAction extends Action
{
    public static $ROUTE_NAME ='registration.birth-date';
    public function getActions()
    {
        return [
            "back" => StartAction::class,
        ];
    }
    public function getNextAction($message)
    {
        info('region redirect');
        return (new RegionAction($this->telegram, $this->telegram_chat, $this->telegram_user))->makeView($message);
    }
    public function makeViewParams($message)
    {
        
        // $inlineLayout = [
        //     [
        //         Keyboard::inlineButton(['text' => $this->translate(" ◀️ Orqaga"), 'callback_data' => self::$ROUTE_NAME . ":back"]),
        //     ]
        //  ];
        return [
            'chat_id' => $this->telegram_chat->chat_id,
            // 'reply_markup' => json_encode([
            //     'inline_keyboard' => $inlineLayout,
            // ]),
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
        $telegram_user = app()->make(TelegramUserService::class)->getOrCreate($this->telegram_chat->chat_id, Action::getUser($message));
        $telegram_user->update(['birth_date' => $message_text]);
    }
}