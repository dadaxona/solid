<?php
namespace Modules\Telegram\Actions\Announcement\Sell;

use Modules\Telegram\Actions\Action;
use Telegram\Bot\Keyboard\Keyboard;

class DescriptionAction extends Action
{
    public static $ROUTE_NAME ='sell.create.description';
    public function getActions()
    {
        return [
            "back" => ImageAction::class,
        ];
    }
    public function getNextAction($message)
    {
        return (new ContactAction($this->telegram, $this->telegram_chat, $this->telegram_user))->makeView($message);
    }
    public function makeViewParams($message)
    {
        $inlineLayout = [
            [
                Keyboard::inlineButton(['text' => $this->translate(" ◀️ Orqaga"), 'callback_data' => self::$ROUTE_NAME . ":back"]),
            ]
         ];
        return [
            'chat_id' => $this->telegram_chat->chat_id,
            // 'reply_markup' => json_encode([
            //     'inline_keyboard' => $inlineLayout,
            // ]),
            'text' => $this->translate("Mahsulot haqida qo'shimcha ma'lumot : ")
        ];
    }

    
    public function proccessMessageData($message)
    {
        $message_text = $this->getMessageText($message);
        $announcement = $this->telegram_chat->announcements()->sells()->draft()->first();
        $announcement->update(['description' => $message_text]);
    }
}