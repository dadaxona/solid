<?php
namespace Modules\Telegram\Actions\Announcement\Admin\Settings;

use Modules\Telegram\Actions\Action;
use Telegram\Bot\Keyboard\Keyboard;
use Illuminate\Support\Str;

class IntervalAction extends Action
{   

    public static $ROUTE_NAME = 'admin.settings.interval';
    public function getActions()
    {
        return [
            'back' => MenuAction::class
        ];
    }
    public function getNextAction($message)
    {
        return  (new MenuAction($this->telegram, $this->telegram_chat, $this->telegram_user))->makeView($message);
    }
    public function makeViewParams($message)
    {
        
        $inlineLayout = [
            [
                Keyboard::inlineButton(['text' => $this->translate("📆 1 kun"), 'callback_data' => self::$ROUTE_NAME . ":interval(1)"]),
                Keyboard::inlineButton(['text' => $this->translate("📆 2 kun"), 'callback_data' => self::$ROUTE_NAME . ":interval(2)"]),
                Keyboard::inlineButton(['text' => $this->translate("📆 3 kun"), 'callback_data' => self::$ROUTE_NAME . ":interval(3)"]),
            ],
            [
                Keyboard::inlineButton(['text' => $this->translate("📆 4 kun"), 'callback_data' => self::$ROUTE_NAME . ":interval(4)"]),
                Keyboard::inlineButton(['text' => $this->translate("📆 5 kun"), 'callback_data' => self::$ROUTE_NAME . ":interval(5)"]),
                Keyboard::inlineButton(['text' => $this->translate("📆 6 kun"), 'callback_data' => self::$ROUTE_NAME . ":interval(6)"]),
            ],            
            [
                Keyboard::inlineButton(['text' => $this->translate("📆 7 kun"), 'callback_data' => self::$ROUTE_NAME . ":interval(7)"]),
            ],
            [
                Keyboard::inlineButton(['text' => $this->translate(" ◀️ Orqaga"), 'callback_data' => self::$ROUTE_NAME . ":back"]),
            ]
        ];
        
        $telegram_bot = $this->telegram_chat->telegram_bot;
        $interval = $telegram_bot->settings['interval']??'0';
        return [
            'chat_id' =>  $this->telegram_chat->chat_id,
            'reply_markup' => json_encode([
                'inline_keyboard' => $inlineLayout,
            ]),
            'text' => "Foydalanuvchi xabarlari orasidagi interval(📆 $interval kun)"
        ];
    }
    
    public function proccessMessageData($message)
    {
        $message_text = $this->getMessageText($message);
        
        $telegram_bot = $this->telegram_chat->telegram_bot;
        $interval = Str::between($message_text, '(', ')');
        $settings = $telegram_bot->settings??[];
        $settings['interval'] = $interval;
        $telegram_bot->update([
            'settings' => $settings
        ]);
    }
    public function middleware()
    {
        return $this->telegram_user->is_admin;
    }

}