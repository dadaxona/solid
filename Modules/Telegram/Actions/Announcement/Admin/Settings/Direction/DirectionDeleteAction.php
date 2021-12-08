<?php
namespace Modules\Telegram\Actions\Announcement\Admin\Settings\Direction;

use Modules\Telegram\Actions\Action;
use Modules\Telegram\Entities\Direction;
use Telegram\Bot\Keyboard\Keyboard;
use Illuminate\Support\Str;

class DirectionDeleteAction extends Action
{
    public static $ROUTE_NAME = 'admin.settings.direction.delete';
    public function getActions()
    {
        return [
            'back' => DirectionListAction::class,
        ];
    }
    public function getNextAction($message)
    {
        $id = Str::between($this->telegram_chat->state, '(', ')');
        $message['message']['text'] = "admin.settings.direction.delete($id)";
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
                Keyboard::inlineButton(['text' => $this->translate("✅ Ha"), 'callback_data' => self::$ROUTE_NAME . ":delete($id)"]),
                Keyboard::inlineButton(['text' => $this->translate("⛔️ Yo'q"), 'callback_data' => self::$ROUTE_NAME . ":back($id)"]),
            ],
        ];
        $reply_markup = [
            'inline_keyboard' => $inlineLayout,
        ];
        return [
            'chat_id' => $this->telegram_chat->chat_id,
            'reply_markup' => json_encode($reply_markup),
            'parse_mode' => 'HTML',
            'text' => $this->translate("<strong> $direction->name </strong> Sohasini o'chirmoqchimisiz?")
        ];
    }

    public function proccessMessageData($message)
    {
        $message_text = $this->getMessageText($message);
        $id = Str::between($this->telegram_chat->state, '(', ')');
        $direction = Direction::find($id);
        $direction->delete();
    }
       
    public function middleware()
    {
        return $this->telegram_user->is_admin;
    }
}