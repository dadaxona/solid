<?php
namespace Modules\Telegram\Actions\Announcement\Registration;

use Modules\Telegram\Actions\Action;
use Modules\Telegram\Entities\Region;
use Modules\Telegram\Services\TelegramUserService;
use Telegram\Bot\Keyboard\Keyboard;
use Illuminate\Support\Str;

class RegionAction extends Action
{
    public static $ROUTE_NAME ='registration.region';
    public function getActions()
    {
        return [
            'back' => BirthDateAction::class
        ];
    }
    public function getNextAction($message)
    {
        return (new DirectionAction($this->telegram, $this->telegram_chat, $this->telegram_user))->makeView($message);
    }

    
    public function makeViewParams($message)
    {
        $inlineLayout = [
            ...$this->getRegionsList()
        ];

        return [
            'chat_id' => $this->telegram_chat->chat_id, 
            'reply_markup' => json_encode([
                'inline_keyboard' => $inlineLayout
            ]),
            'text' => $this->translate("Qaysi viloyatdansiz?")
        ];
    }
    public function proccessMessageData($message)
    {
        
        $message_text = $this->getMessageText($message);
        $id = Str::between($message_text,'(', ')');
        $telegram_user = app()->make(TelegramUserService::class)->getOrCreate($this->telegram_chat->chat_id, Action::getUser($message));
        $telegram_user->update(['region_id' => $id]);
    }
    public function getRegionsList()
    {
        $res = [];
        $regions = Region::all()->chunk(3);
        foreach($regions as $region_chunk){
            $chunk = [];
            foreach ($region_chunk as  $region) {
                $chunk[] = Keyboard::inlineButton(['text' => $region->name, 'callback_data' => ":region($region->id)"]);
            }
            $res[] = $chunk;
        }
        return $res;
        
    }
}