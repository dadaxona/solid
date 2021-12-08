<?php
namespace Modules\Telegram\Actions\Announcement\Admin\Statistics;

use Modules\Telegram\Actions\Action;
use Modules\Telegram\Actions\Announcement\Admin\MenuAction;
use Telegram\Bot\Keyboard\Keyboard;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class StatisticsAction extends Action
{
    public static $ROUTE_NAME = 'admin.statistics';
    public function getActions()
    {
        return [
            "user" =>UsersAction::class ,
            "direction" => DirectionsAction::class,
            "region" => RegionsAction::class,
            "back" => MenuAction::class

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
                Keyboard::inlineButton(['text' => $this->translate("👤 Foydalanuvchi bo'yicha"), 'callback_data' => self::$ROUTE_NAME . ":user"]),
            ],
            [  
                Keyboard::inlineButton(['text' => $this->translate("Soha bo'yicha"), 'callback_data' => self::$ROUTE_NAME . ":direction"]),
            ],
            [
                Keyboard::inlineButton(['text' => $this->translate("Hudud bo'yicha"), 'callback_data' => self::$ROUTE_NAME . ":region"]),
            ],
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
            'text' =>  $this->translate("Statistika")
        ];
    }
    public function validateMessage($message)
    {
        $message_text = $this->getMessageText($message);
        $rules = ['message_text' => ['string',  Rule::in(["user","region", "direction", "back"]),]];

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