<?php
namespace Modules\Telegram\Actions\Announcement\Sell;

use Modules\Telegram\Actions\Action;
use Telegram\Bot\Keyboard\Keyboard;
use Illuminate\Support\Str;

class ImageAction extends Action
{
    public static $ROUTE_NAME ='sell.create.image';
    public function getActions()
    {
        return [
            "back" => RegionAction::class,
        ];
    }
    public function getNextAction($message)
    {
        return (new DescriptionAction($this->telegram, $this->telegram_chat, $this->telegram_user))->makeView($message);
    }

    public function makeView($message)
    {
        $this->telegram_chat->update(['state' => $this::$ROUTE_NAME]);
        $params = $this->makeViewParams($message);
        $this->sendTelegramMessage($params);
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
            'text' => $this->translate("Mahsulot rasmini joylashtiring : "),
        ];
    }
    public function validateMessage($message)
    {
        $file = $this->getPhoto($message);
        if(isset($file['mime_type'])){
            return Str::contains($file['mime_type'], 'image');
        }
        return ($file != null);
    }
    public function proccessMessageData($message)
    {
        $file = $this->getPhoto($message);
        // $path = $this->downloadFile($file);
        $announcement = $this->telegram_chat->announcements()->sells()->draft()->first();
        $announcement->update(['image' => $file['file_id']]);
    }
}