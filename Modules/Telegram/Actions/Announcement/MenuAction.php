<?php
namespace Modules\Telegram\Actions\Announcement;

use Modules\Telegram\Actions\Action;
use Modules\Telegram\Actions\Announcement\Admin\MenuAction as AdminMenuAction;
use Modules\Telegram\Actions\Announcement\Purchase\NameAction as PurchaseNameAction;
use Modules\Telegram\Actions\Announcement\Sell\NameAction;
use Modules\Telegram\Actions\Announcement\Settings\MenuAction as SettingsMenuAction;
use Telegram\Bot\Keyboard\Keyboard;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class MenuAction extends Action
{
    public static $ROUTE_NAME ='menu';
    
    public function getActions()
    {
        return [
            "sell" => NameAction::class,
            "purchase" => PurchaseNameAction::class,
            "my_announcements" => AnnouncementsListAction::class,
            "settings" => SettingsMenuAction::class,
            "admin_menu" => AdminMenuAction::class,
            
        ];
    }
    public function makeViewParams($message)
    {
        $inlineLayout = [
            [
                Keyboard::inlineButton(['text' => $this->translate("↖️ Sotish"), 'callback_data' => self::$ROUTE_NAME . ":sell"]),
                Keyboard::inlineButton(['text' => $this->translate("↘️ Sotib olish"), 'callback_data' => self::$ROUTE_NAME . ":purchase"]),
            ],
            [
                Keyboard::inlineButton(['text' => $this->translate("📃 Mening e'lonlarim"), 'callback_data' => self::$ROUTE_NAME . ":my_announcements"]),
                Keyboard::inlineButton(['text' => $this->translate("🛠 Sozlamalar"), 'callback_data' => self::$ROUTE_NAME . ":settings"]),
            ],
        ];
        if($this->telegram_user->is_admin){
            $inlineLayout[] = [
                Keyboard::inlineButton(['text' => $this->translate("👨🏻‍💻 Admin"), 'callback_data' => self::$ROUTE_NAME . ":admin_menu"]),
            ];
        }

        return [
            'chat_id' => $this->telegram_chat->chat_id,
            'reply_markup' => json_encode([
                'inline_keyboard' => $inlineLayout,
            ]),
            'text' => $this->translate("Asosiy menu")
        ];
    }
    public function validateMessage($message)
    {
        $message_text = $this->getMessageText($message);
        $rules = ['message_text' => ['string',  Rule::in(["sell", "purchase", "my_announce","settings","admin_menu","back"]),]];

        $input = ['message_text' => $message_text];

        return Validator::make($input, $rules)->passes();
    }
    public function proccessMessageData($message)
    {
        $message_text = $this->getMessageText($message);
    }


}