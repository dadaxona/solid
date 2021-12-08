<?php
namespace Modules\Telegram\Actions\Announcement\Settings;

use Modules\Telegram\Actions\Action;
use Modules\Telegram\Services\TelegramUserService;
use Telegram\Bot\Keyboard\Keyboard;
use Illuminate\Support\Facades\Validator;

class NameAction extends Action
{
    public static $ROUTE_NAME ='settings.update.name';
    public function getActions()
    {
        return [
            'cancel' => MenuAction::class,
        ];
    }
    public function getNextAction($message)
    {
        return (new MenuAction($this->telegram, $this->telegram_chat, $this->telegram_user))->makeView($message);
    }

    
    public function makeViewParams($message)
    {
        $inlineLayout = [
            [
                Keyboard::inlineButton(['text' => $this->translate("🚫 Bekor qilish"),'callback_data' => self::$ROUTE_NAME . ":cancel"]),
            ],
        ];
        $telegram_user = app()->make(TelegramUserService::class)->getOrCreate($this->telegram_chat->chat_id, Action::getUser($message));
        return [
            'chat_id' => $this->telegram_chat->chat_id,
            'parse_mode' => 'HTML',
            'reply_markup' => json_encode([
                'inline_keyboard' => $inlineLayout,
            ]),
            'text' => "Ism (<strong>$telegram_user->name</strong>):"
        ];
    }
    public function validateMessage($message)
    {
        $message_text = $this->getMessageText($message);
        $rules = ['message_text' => ['string',  'min:3', 'max:100']];
        $input = ['message_text' => $message_text];
        return Validator::make($input, $rules)->passes();
    }
    public function proccessMessageData($message)
    {
        $message_text = $this->getMessageText($message);
        $telegram_user = app()->make(TelegramUserService::class)->getOrCreate($this->telegram_chat->chat_id, Action::getUser($message));
        $telegram_user->update(['name' => $message_text]);
    }
}