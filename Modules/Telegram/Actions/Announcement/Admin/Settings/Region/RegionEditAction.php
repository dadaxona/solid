<?php
namespace Modules\Telegram\Actions\Announcement\Admin\Settings\Region;

use Modules\Telegram\Actions\Action;
use Modules\Telegram\Entities\Region;
use Telegram\Bot\Keyboard\Keyboard;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RegionEditAction extends Action
{
    public static $ROUTE_NAME = 'admin.settings.region.edit';
    public function getActions()
    {
        return [
            'back' =>RegionShowAction::class,
        ];
    }
    public function getNextAction($message)
    {
        $id = Str::between($this->telegram_chat->state, '(', ')');
        $message['message']['text'] = "admin.settings.region.edit($id)";
        return (new RegionShowAction($this->telegram, $this->telegram_chat, $this->telegram_user))->makeView($message);
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
        $region = Region::find($id);
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
            'text' => "Hudud yangi nomini kiriting(<strong>$region->name</strong>):"
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
        
        $id = Str::between($this->telegram_chat->state, '(', ')');
        $region = Region::find($id);
        $region->update(['name' => $message_text]);
    }
    public function middleware()
    {
        return $this->telegram_user->is_admin;
    }
}