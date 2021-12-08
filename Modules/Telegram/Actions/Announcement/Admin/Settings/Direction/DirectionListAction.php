<?php
namespace Modules\Telegram\Actions\Announcement\Admin\Settings\Direction;

use Modules\Telegram\Actions\Action;
use Modules\Telegram\Actions\Announcement\Admin\Settings\MenuAction;
use Modules\Telegram\Entities\Direction;
use Telegram\Bot\Keyboard\Keyboard;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class DirectionListAction extends Action
{
    public static $ROUTE_NAME = 'admin.settings.direction.list';
    public function getActions()
    {
        return [
            'back' => MenuAction::class,
            'create' => DirectionCreateAction::class,
            'show' => DirectionShowAction::class,
        ];
    }
    public function getNextAction($message)
    {
        return (new MenuAction($this->telegram, $this->telegram_chat, $this->telegram_user))->makeView($message);
    }

    
    public function makeViewParams($message)
    {
        
        $inlineLayout = [
            ...$this->getDirectionList(),
            [
                Keyboard::inlineButton(['text' => $this->translate("➕ Qo'shish"), 'callback_data' => self::$ROUTE_NAME . ":create"]),
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
            'text' => 'Sohalar'
        ];
    }
    public function validateMessage($message)
    {
        $message_text = $this->getMessageText($message);
        $rules = ['message_text' => ['string',  Rule::in(["edit","back"]),]];

        $input = ['message_text' => $message_text];

        return Validator::make($input, $rules)->passes();
    }
    public function proccessMessageData($message)
    {
        $message_text = $this->getMessageText($message);
        
    }
    
    public function getDirectionList()
    {
        $res = [];
        $directions = Direction::all();
        foreach($directions as $direction){
            $res[] = [
                Keyboard::inlineButton(['text' => $direction->name, 'callback_data' => self::$ROUTE_NAME . ":show($direction->id)"]),
            ];
        }
        return $res;
    }
    public function middleware()
    {
        return $this->telegram_user->is_admin;
    }
}