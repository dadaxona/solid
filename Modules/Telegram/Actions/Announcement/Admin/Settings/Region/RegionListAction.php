<?php
namespace Modules\Telegram\Actions\Announcement\Admin\Settings\Region;

use Modules\Telegram\Actions\Action;
use Modules\Telegram\Actions\Announcement\Admin\Settings\MenuAction;
use Modules\Telegram\Entities\Region;
use Telegram\Bot\Keyboard\Keyboard;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class RegionListAction extends Action
{
    public static $ROUTE_NAME = 'admin.settings.region.list';
    public function getActions()
    {
        return [
            'back' => MenuAction::class,
            'create' => RegionCreateAction::class,
            'show' => RegionShowAction::class,
        ];
    }
    public function getNextAction($message)
    {
        return (new MenuAction($this->telegram, $this->telegram_chat, $this->telegram_user))->makeView($message);
    }

    
    public function makeViewParams($message)
    {
        
        $inlineLayout = [
            ...$this->getRegionList(),
            [
                Keyboard::inlineButton(['text' => $this->translate("➕ Qo'shish"), 'callback_data' => self::$ROUTE_NAME . ":create"]),
            ],
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
            'text' => 'Hududlar'
        ];
    }
    public function validateMessage($message)
    {
        $message_text = $this->getMessageText($message);
        $rules = ['message_text' => ['string',  Rule::in(["edit","back"]),]];

        $input = ['message_text' => $message_text];

        return Validator::make($input, $rules)->passes();
    }
    public function proccessMessageData($message)
    {
        $message_text = $this->getMessageText($message);
        
    }
    public function getRegionList()
    {
        $res = [];
        $regions = Region::all();
        foreach($regions as $region){
            $res[] = [
                Keyboard::inlineButton(['text' => $region->name, 'callback_data' => self::$ROUTE_NAME . ":show($region->id)"]),
            ];
        }
        return $res;
    }
    public function middleware()
    {
        return $this->telegram_user->is_admin;
    }
}