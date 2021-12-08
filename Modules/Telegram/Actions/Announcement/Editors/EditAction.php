<?php
namespace Modules\Telegram\Actions\Announcement\Editors;

use Modules\Telegram\Actions\Action;
use Modules\Telegram\Actions\Announcement\MenuAction;
use Modules\Telegram\Actions\Announcement\Sell\ConfirmationAction;
use Telegram\Bot\Keyboard\Keyboard;

class EditAction extends Action
{
    public static $ROUTE_NAME = 'editors.edit';
    public function getActions()
    {
        return [
            "back" => ConfirmationAction::class,
        ];
    }
    public function getNextAction($message)
    {
        return (new MenuAction($this->telegram, $this->telegram_chat, $this->telegram_user))->makeView($message);
    }
    public function makeViewParams($message)
    {
        if(isset($message['callback_query'])){
            $message_id = $message['callback_query']['message']['message_id'];
            $chat_id = $message['callback_query']['message']['chat']['id'];
            $reply_markup = $params['reply_markup']??json_encode([]);
            $this->telegram->editMessageReplyMarkup([
                'chat_id' => $chat_id,
                'message_id' => $message_id,
                'reply_markup' => json_encode([
                    'inline_keyboard' => null,
                ]),
            ]);
        }
        
        $inlineLayout = [
            [
                Keyboard::inlineButton(['text' => $this->translate(" ◀️ Orqaga"), 'callback_data' => self::$ROUTE_NAME . ":back"]),
            ],
        ];
        $reply_markup = [
            'inline_keyboard' => $inlineLayout,
        ];
        return [
            'chat_id' => $this->telegram_chat->chat_id,
            'reply_markup' => json_encode($reply_markup),
            'text' => $this->translate("E'lonni tahriralmoqchimisiz: ")
        ];
    }

    public function proccessMessageData($message)
    {
        $message_text = $this->getMessageText($message);
    }
}