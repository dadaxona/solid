<?php
namespace Modules\Telegram\Actions\Announcement\Admin;

use Modules\Telegram\Actions\Action;
use Modules\Telegram\Actions\Announcement\Admin\Announcements\MenuAction as AnnouncementsMenuAction;
use Modules\Telegram\Actions\Announcement\Admin\Members\MenuAction as MembersMenuAction;
use Modules\Telegram\Actions\Announcement\Admin\Settings\MenuAction as SettingsMenuAction;
use Modules\Telegram\Actions\Announcement\Admin\Statistics\StatisticsAction;
use Modules\Telegram\Actions\Announcement\MenuAction as AnnouncementMenuAction;
use Telegram\Bot\Keyboard\Keyboard;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;


class MenuAction extends Action
{   

    public static $ROUTE_NAME = 'admin.menu';
    public function getActions()
    {
        return [
            "settings" =>SettingsMenuAction::class,
            "statistics" => StatisticsAction::class,
            "announce" => AnnouncementsMenuAction::class,
            "user_info" => MembersMenuAction::class,
            "message_for_user" => Action::class,
            "back" => AnnouncementMenuAction::class,
        ];
    }
    public function makeViewParams($message)
    {
        $inlineLayout = [
            [
                Keyboard::inlineButton(['text' => $this->translate("🛠 Sozlamalar"), 'callback_data' => self::$ROUTE_NAME . ":settings"]),
                Keyboard::inlineButton(['text' => $this->translate("📊 Statistika"), 'callback_data' => self::$ROUTE_NAME . ":statistics"]),
            ],[
                Keyboard::inlineButton(['text' => $this->translate("📃 E'lonlar"), 'callback_data' => self::$ROUTE_NAME . ":announce"]),
                Keyboard::inlineButton(['text' => $this->translate("👥 Foydalanuvchi ma'lumoti"), 'callback_data' => self::$ROUTE_NAME . ":user_info"]),
            ],
            // [
            //     Keyboard::inlineButton(['text' => $this->translate("Foydalanuvchiga xabar yuborish"), 'callback_data' => self::$ROUTE_NAME . ":message_for_user"]),
            // ],
            [
                Keyboard::inlineButton(['text' => $this->translate(" ◀️ Orqaga"), 'callback_data' => self::$ROUTE_NAME . ":back"]),
            ],
        ];

        return [
            'chat_id' => $this->telegram_chat->chat_id,
            'reply_markup' => json_encode([
                'inline_keyboard' => $inlineLayout,
            ]),
            'text' => $this->translate("Admin menusi")
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
    public function middleware()
    {
        return $this->telegram_user->is_admin;
    }

}