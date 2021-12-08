<?php
namespace Modules\Telegram\Actions\Announcement\Settings;

use Modules\Telegram\Actions\Action;
use Modules\Telegram\Actions\Announcement\MenuAction as AnnouncementMenuAction;
use Modules\Telegram\Services\TelegramUserService;
use Telegram\Bot\Keyboard\Keyboard;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class MenuAction extends Action
{
    public static $ROUTE_NAME ='settings.menu';
    public function getActions()
    {
        return [
            'back' => AnnouncementMenuAction::class,
            'name' => NameAction::class,
            'birth_date' => BirthDateAction::class,
            'region' => RegionAction::class,
            'direction' => DirectionAction::class
        ];
    }
    public function getNextAction($message)
    {
        return (new MenuAction($this->telegram, $this->telegram_chat, $this->telegram_user))->makeView($message);
    }

    
    public function makeViewParams($message)
    {
        
        $telegram_user = app()->make(TelegramUserService::class)->getOrCreate($this->telegram_chat->chat_id, Action::getUser($message));
        $region_name = '-';
        $direction_name = '-';
        if($telegram_user->region){
            $region_name = $telegram_user->region->name;
        }
        if($telegram_user->direction){
            $direction_name = $telegram_user->direction->name;
        }
        $inlineLayout = [
            [
                Keyboard::inlineButton(['text' => $this->translate("Ismni o'gartirish: $telegram_user->name"), 'callback_data' => self::$ROUTE_NAME . ":name"]),
            ],[
                Keyboard::inlineButton(['text' => $this->translate("Tug'ilgan sanani o'zgartirish: $telegram_user->birth_date"), 'callback_data' => self::$ROUTE_NAME . ":birth_date"]),
            ],[
                Keyboard::inlineButton(['text' => $this->translate("Hududni o'zgartirish: $region_name"), 'callback_data' => self::$ROUTE_NAME . ":region"]),
            ],[
                Keyboard::inlineButton(['text' => $this->translate("Sohani o'zgartirish: $direction_name"), 'callback_data' => self::$ROUTE_NAME . ":direction"]),
            ],[
                Keyboard::inlineButton(['text' => $this->translate(" ◀️ Orqaga"), 'callback_data' => self::$ROUTE_NAME . ":back"]),
            ],
        ];

        return [
            'chat_id' => $this->telegram_chat->chat_id,
            'reply_markup' => json_encode([
                'inline_keyboard' => $inlineLayout,
            ]),
            'text' => $this->translate("Ma'lumotlarni tahrirlash:")
        ];
    }
    public function validateMessage($message)
    {
        $message_text = $this->getMessageText($message);
        $rules = ['message_text' => ['string',  Rule::in(["name", "birth_date", "region", "direction"]),]];

        $input = ['message_text' => $message_text];

        return Validator::make($input, $rules)->passes();
    }
    public function proccessMessageData($message)
    {
        $message_text = $this->getMessageText($message);
        
    }
}