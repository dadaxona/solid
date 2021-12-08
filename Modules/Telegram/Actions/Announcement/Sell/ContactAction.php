<?php
namespace Modules\Telegram\Actions\Announcement\Sell;

use Modules\Telegram\Actions\Action;
use Telegram\Bot\Keyboard\Keyboard;

class ContactAction extends Action
{
    
    public static $ROUTE_NAME = 'sell.create.contact';
    public function getActions()
    {
        return [
            "back" => DescriptionAction::class,
        ];
    }
    public function getNextAction($message)
    {
        return (new ConfirmationAction($this->telegram, $this->telegram_chat, $this->telegram_user))->makeView($message);
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
            'text' => $this->translate("Aloqaga chiquvchi ma'lumotlari(telefon raqam yoki telegram manzil) : ")
        ];
    }

    
    public function proccessMessageData($message)
    {
        $message_text = $this->getMessageText($message);
        $announcement = $this->telegram_chat->announcements()->sells()->draft()->first();
        $announcement->update(['contact' => $message_text]);
    }
}