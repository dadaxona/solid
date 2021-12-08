<?php
namespace Modules\Telegram\Actions\Announcement\Purchase;

use Modules\Telegram\Actions\Action;
use Modules\Telegram\Actions\Announcement\MenuAction;
use Modules\Telegram\Services\AnnouncementService;
use Illuminate\Support\Facades\Validator;
use Telegram\Bot\Keyboard\Keyboard;


class NameAction extends Action
{
    public static $ROUTE_NAME ='purchase.create.name';
    public function getActions()
    {
        return [
            "back" => MenuAction::class,
        ];
    }
    public function getNextAction($message)
    {
        return (new PriceAction($this->telegram, $this->telegram_chat, $this->telegram_user))->makeView($message);
    }
    public function makeView($message)
    {
        $this->telegram_chat->update(['state' => $this::$ROUTE_NAME]);

        $announcement = $this->telegram_chat->announcements()->purchases()->draft()->where('type', 'buy')->first();
        if($announcement){
            $id = $announcement->id;
            $message['callback_query']['data'] = "sell.publish($id)";
            return (new ConfirmationAction($this->telegram, $this->telegram_chat, $this->telegram_user))->makeView($message);
        } else {
            return $this->telegram->sendMessage($this->makeViewParams($message));
        }
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
            'text' => $this->translate("Mahsulot nomi(kamida 3ta harf): ")
        ];
    }
    
    public function validateMessage($message)
    {
        $message_text = $this->getMessageText($message);
        $rules = ['message_text' => 'min:3'];

        $input = ['message_text' => $message_text];

        return Validator::make($input, $rules)->passes();
    }
    public function proccessMessageData($message)
    {
        $message_text = $this->getMessageText($message);
        $announcement = $this->telegram_chat->announcements()->purchases()->draft()->where('type', 'buy')->first();
        if(!$announcement){
            app()->make(AnnouncementService::class)->create([
                'name' => $message_text,
                'type' => 'buy',//TODO purchase qilish kerak
                'telegram_chat_id' => $this->telegram_chat->id
            ]);
        }
    }
}