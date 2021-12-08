<?php
namespace Modules\Telegram\Actions\Announcement\AllAnnounce;

use Modules\Telegram\Actions\Action;
use Modules\Telegram\Actions\Announcement\MenuAction;
use Telegram\Bot\Keyboard\Keyboard;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class InfoAction extends Action
{
    public static $ROUTE_NAME = 'all_announce.create.info';
    public function getActions()
    {
        return [
                "back" => MenuAction::class,
                'reset' => ResetAction::class
        ];
    }
    public function getNextAction($message)
    {
        return (new MenuAction($this->telegram, $this->telegram_chat, $this->telegram_user))->makeView($message);
    }

    
    public function makeViewParams($message)
    {
        $inlineLayout = [
            [
                Keyboard::inlineButton(['text' => $this->translate("🔄 Qayta joylashtirish "), 'callback_data' => self::$ROUTE_NAME . ":reset"]),
                Keyboard::inlineButton(['text' => $this->translate(" ◀️ Orqaga"), 'callback_data' => self::$ROUTE_NAME . ":back"]),
            
            ],
        ];
        return [
            'chat_id' => $this->telegram_chat->chat_id,
            'parse_mode' => 'HTML',
            'reply_markup' => json_encode([
                'inline_keyboard' => $inlineLayout,
            ]),
            'text' => $this->getPreview()
        ];
    }
    public function validateMessage($message)
    {
        $message_text = $this->getMessageText($message);
        $rules = ['message_text' => ['string',  Rule::in(["reset"]),]];

        $input = ['message_text' => $message_text];

        return Validator::make($input, $rules)->passes();
    }
    public function proccessMessageData($message)
    {
        $message_text = $this->getMessageText($message);
        
    }
    public function getPreview()
    {
        // keylar tarjima
        $ans = [
            $this->translate("Maxsulot nomi:") => 'Mahsulot',
            $this->translate("Maxsulot narxi:") => '100.000 so\'m',
            $this->translate("Maxsulot rasmi:") => '-',
            $this->translate("Maxsulothaqida qo'shimcha ma'lumotlar:") => 'qo\'shimcha',
            $this->translate("Maxsulot sotish hududi:") => 'Parkent',
            $this->translate("Aloqa chiquvchi ma'lumoti:") => 'Alisher 98121222',
           
        ];
        // tarjima
        $text = $this->translate("E'lonni qayta joylashtirish ");
        foreach($ans as $key => $val){
            $text .= "\n<strong>" . $key . '</strong> ' . $val;
        }
        return $text;
    }
}