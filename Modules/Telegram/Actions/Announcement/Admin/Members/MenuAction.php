<?php
namespace Modules\Telegram\Actions\Announcement\Admin\Members;

use Modules\Telegram\Actions\Action;
use Modules\Telegram\Actions\Announcement\Admin\MenuAction as AdminMenuAction;
use Modules\Telegram\Entities\TelegramUser;
use Telegram\Bot\Keyboard\Keyboard;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class MenuAction extends Action
{
    public static $ROUTE_NAME = 'admin.members.menu';
    public function getActions()
    {
        return [
            "regions" => RegionsAction::class,
            "directions" => DirectionsAction::class ,
            "download" => DownloadAction::class,
            "back" => AdminMenuAction::class,
        ];
    }
    public function getNextAction($message)
    {
        return (new AdminMenuAction($this->telegram, $this->telegram_chat, $this->telegram_user))->makeView($message);
    }

    
    public function makeViewParams($message)
    {
        
        $inlineLayout = [
            [ 
                Keyboard::inlineButton(['text' => $this->translate("Huddular bo'yicha"), 'callback_data' => self::$ROUTE_NAME . ":regions"]),
            ],
            [
                Keyboard::inlineButton(['text' => $this->translate("Sohalar bo'yicha"), 'callback_data' => self::$ROUTE_NAME . ":directions"]),
            ], 
            [ 
                Keyboard::inlineButton(['text' => $this->translate("📲 Yuklab olish"), 'callback_data' => self::$ROUTE_NAME . ":download"]),
            ],
            [ 
                Keyboard::inlineButton(['text' => $this->translate(" ◀️ Orqaga"), 'callback_data' => self::$ROUTE_NAME . ":back"]),
            ],
            
        ];
        $users_count = TelegramUser::count();
        return [
            'chat_id' => $this->telegram_chat->chat_id,
            'parse_mode' => 'HTML',
            'reply_markup' => json_encode([
                'inline_keyboard' => $inlineLayout,
            ]),
            'text' => "Foydalanuvchilar ma'lumotlari:\nUmumiy foydalanuvchilar soni <strong>$users_count</strong> ta"
        ];
    }
    public function validateMessage($message)
    {
        $message_text = $this->getMessageText($message);
        $rules = ['message_text' => ['string',  Rule::in(["newly-added","by-regions", "by-directions","save","back"]),]];

        $input = ['message_text' => $message_text];

        return Validator::make($input, $rules)->passes();
    }
    public function proccessMessageData($message)
    {
        $message_text = $this->getMessageText($message);
    }
    public function middleware()
    {
        return $this->telegram_user->is_admin;
    }
}