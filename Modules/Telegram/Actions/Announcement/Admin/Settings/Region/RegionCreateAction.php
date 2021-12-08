<?php
namespace Modules\Telegram\Actions\Announcement\Admin\Settings\Region;

use Modules\Telegram\Actions\Action;
use Modules\Telegram\Entities\Region;
use Telegram\Bot\Keyboard\Keyboard;
use Illuminate\Support\Facades\Validator;

class RegionCreateAction extends Action
{
    public static $ROUTE_NAME = 'admin.settings.region.create';
    public function getActions()
    {
        return [
            'back' => RegionListAction::class,
        ];
    }
    public function getNextAction($message)
    {
        return (new RegionListAction($this->telegram, $this->telegram_chat, $this->telegram_user))->makeView($message);
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
            'text' => 'Hudud nomini kiriting:'
        ];
    }
    public function validateMessage($message)
    {
        $message_text = $this->getMessageText($message);
        $rules = ['message_text' => ['string', 'min:3']];

        $input = ['message_text' => $message_text];

        return Validator::make($input, $rules)->passes();
    }
    public function proccessMessageData($message)
    {
        $message_text = $this->getMessageText($message);
        Region::create(['name' => $message_text]);
    }
    public function middleware()
    {
        return $this->telegram_user->is_admin;
    }
}