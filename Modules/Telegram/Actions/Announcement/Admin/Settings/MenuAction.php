<?php
namespace Modules\Telegram\Actions\Announcement\Admin\Settings;

use Modules\Telegram\Actions\Action;
use Modules\Telegram\Actions\Announcement\Admin\MenuAction as AdminMenuAction;
use Modules\Telegram\Actions\Announcement\Admin\Settings\Direction\DirectionListAction;
use Modules\Telegram\Actions\Announcement\Admin\Settings\IntervalAction;
use Modules\Telegram\Actions\Announcement\Admin\Settings\Region\RegionListAction;
use Modules\Telegram\Entities\TelegramChat;
use Modules\Telegram\Entities\TelegramUser;
use Modules\Telegram\Services\TelegramUserService;
use Illuminate\Support\Arr;
use Telegram\Bot\Keyboard\Keyboard;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class MenuAction extends Action
{
    public static $ROUTE_NAME = 'admin.settings.menu';
    public function getActions()
    {
        return [
            'region' => RegionListAction::class,
            'direction' => DirectionListAction::class,
            'interval-edit' => IntervalAction::class,
            'back' => AdminMenuAction::class
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
                Keyboard::inlineButton(['text' => $this->translate("Hududlar"), 'callback_data' => self::$ROUTE_NAME . ":region"]),
                Keyboard::inlineButton(['text' => $this->translate("Sohalar"), 'callback_data' => self::$ROUTE_NAME . ":direction"]),
            ],
            [
                Keyboard::inlineButton(['text' => $this->translate("⏱ E'lon berish limiti"), 'callback_data' => self::$ROUTE_NAME . ":interval-edit"]),
            ],
            [
                Keyboard::inlineButton(['text' => $this->translate("👨🏻‍💻 Adminlarni qayta yuklash"), 'callback_data' => self::$ROUTE_NAME . ":refresh-admins"]),
            ],
            [
                Keyboard::inlineButton(['text' => $this->translate(" ◀️ Orqaga"), 'callback_data' => self::$ROUTE_NAME . ":back"]),
            ]
        ];
        
        return [
            'chat_id' => $this->telegram_chat->chat_id,
            'parse_mode' => 'HTML',
            'reply_markup' => json_encode([
                'inline_keyboard' => $inlineLayout,
            ]),
            'text' => 'Admin oynasi'
        ];
    }
    public function validateMessage($message)
    {
        $message_text = Str::between($this->getMessageText($message),':', '(');
        $rules = ['message_text' => ['string',  Rule::in(["refresh-admins"]),]];

        $input = ['message_text' => $message_text];

        return Validator::make($input, $rules)->passes();
    }
    public function proccessMessageData($message)
    {
        $message_text = Str::between($this->getMessageText($message),':', '(');
        if($message_text == 'refresh-admins'){
            $chats = TelegramChat::where('chat_id', '<', 0)->get();
            TelegramUser::query()->update(['is_admin' => false]);
            foreach($chats as $chat){
                try {
                    $users = $this->telegram->getChatAdministrators(['chat_id' => $chat->chat_id]);
                    $users = Arr::pluck($users, 'user');
                    info($users);
                    foreach ($users as $user) {
                        $telegram_user = app()->make(TelegramUserService::class)->getOrCreate($chat->id,  $user);
                        $telegram_user->update(['is_admin' => true]);
                    }
                } catch (\Throwable $th) {
                    info($th);
                }
                    
            }
        }
    }
    public function middleware()
    {
        return $this->telegram_user->is_admin;
    }
}