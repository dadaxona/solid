<?php
namespace Modules\Telegram\Actions\Announcement\Admin\Settings\Direction;

use Modules\Telegram\Actions\Action;
use Modules\Telegram\Entities\Direction;
use Telegram\Bot\Keyboard\Keyboard;
use Illuminate\Support\Facades\Validator;

class DirectionCreateAction extends Action
{
    public static $ROUTE_NAME = 'admin.settings.direction.create';
    public function getActions()
    {
        return [
            'back' => DirectionListAction::class,
        ];
    }
    public function getNextAction($message)
    {
        return (new DirectionListAction($this->telegram, $this->telegram_chat, $this->telegram_user))->makeView($message);
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
            'text' => 'Soha nomini kiriting:'
        ];
    }
    public function validateMessage($message)
    {
        $message_text = $this->getMessageText($message);
        $rules = ['message_text' => ['string', 'min:2']];

        $input = ['message_text' => $message_text];

        return Validator::make($input, $rules)->passes();
    }
    public function proccessMessageData($message)
    {
        $message_text = $this->getMessageText($message);
        Direction::create(['name' => $message_text]);
    }
    public function middleware()
    {
        return $this->telegram_user->is_admin;
    }
}