<?php
namespace Modules\Telegram\Actions\Announcement\Purchase;

use Modules\Telegram\Actions\Action;
use Telegram\Bot\Keyboard\Keyboard;

class PriceAction extends Action
{
    public static $ROUTE_NAME ='purchase.create.price';
    public function getActions()
    {
        return [
            "back" => NameAction::class,
        ];
    }
    public function getNextAction($message)
    {
        return (new RegionAction($this->telegram, $this->telegram_chat, $this->telegram_user))->makeView($message);
    }
    public function makeViewParams($message)
    {$inlineLayout = [
        [
            Keyboard::inlineButton(['text' => $this->translate(" ◀️ Orqaga"), 'callback_data' => self::$ROUTE_NAME . ":back"]),
        ]
    ];
    return [
        'chat_id' => $this->telegram_chat->chat_id,
        // 'reply_markup' => json_encode([
        //     'inline_keyboard' => $inlineLayout,
        // ]),
        'text' => $this->translate("Narxi:")
    ];
    }
    

    public function proccessMessageData($message)
    {
        $message_text = $this->getMessageText($message);
        
        $announcement = $this->telegram_chat->announcements()->purchases()->draft()->first();
        $announcement->update(['price' => $message_text]);
    }
}