<?php
namespace Modules\Telegram\Actions\Announcement\Admin\Settings\Direction;

use Modules\Telegram\Actions\Action;
use Modules\Telegram\Entities\Direction;
use Telegram\Bot\Keyboard\Keyboard;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class DirectionShowAction extends Action
{
    public static $ROUTE_NAME = 'admin.settings.direction.show';
    public function getActions()
    {
        return [
            'back' => DirectionListAction::class,
            'edit' => DirectionEditAction::class,
            'delete' => DirectionDeleteAction::class,
        ];
    }
    public function getNextAction($message)
    {
        return (new DirectionListAction($this->telegram, $this->telegram_chat, $this->telegram_user))->makeView($message);
    }

    public function makeView($message)
    {
        $message_text = $this->getMessageText($message);
        $this->telegram_chat->update(['state' => $this::$ROUTE_NAME . '(' . Str::between($message_text,'(', ')') . ')']);

        return $this->telegram->sendMessage($this->makeViewParams($message));
    }
    
    public function makeViewParams($message)
    {
        $message_text = $this->getMessageText($message);
        $id = Str::between($message_text,'(', ')');
        $direction = Direction::find($id);
        
        $inlineLayout = [
            [
                Keyboard::inlineButton(['text' => $this->translate("🖊 Tahrirlash"), 'callback_data' => self::$ROUTE_NAME . ":edit($id)"]),
                Keyboard::inlineButton(['text' => $this->translate("🚫 O'chirish"), 'callback_data' => self::$ROUTE_NAME . ":delete($id)"]),
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
            'text' => "Soha: <strong>$direction->name</strong>"
        ];
    }
    public function validateMessage($message)
    {
        $message_text = $this->getMessageText($message);
        $rules = ['message_text' => ['string',  Rule::in(["back", "save"]),]];

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