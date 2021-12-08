<?php
namespace Modules\Telegram\Actions\Announcement\Sell;

use Modules\Telegram\Actions\Action;
use Modules\Telegram\Entities\Region;
use Telegram\Bot\Keyboard\Keyboard;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class RegionAction extends Action
{
    public static $ROUTE_NAME ='sell.create.region';
    public function getActions()
    {
        return [
            // 'ask_region' => AskRegion::class
        ];
    }
    public function getNextAction($message)
    {
        return (new ImageAction($this->telegram, $this->telegram_chat, $this->telegram_user))->makeView($message);
    }


    public function makeViewParams($message)
    {
        $inlineLayout = [
            ...$this->getRegionsList()
        ];
        
        $reply_markup = [
            'inline_keyboard' => $inlineLayout,
        ];
        return [
            'chat_id' => $this->telegram_chat->chat_id, 
            'reply_markup' => json_encode($reply_markup),
            'text' => $this->translate("Qaysi hududda sotasiz? :")
        ];
    }
    public function validateMessage($message)
    {
        $message_text = $this->getMessageText($message);
        $id = Str::between($message_text,'(', ')');
        $rules = ['id' => ['exists:regions,id']];

        $input = ['id' => $id];

        return Validator::make($input, $rules)->passes();
    }
    public function proccessMessageData($message)
    {
        $message_text = $this->getMessageText($message);
        $id = Str::between($message_text,'(', ')');
        $announcement = $this->telegram_chat->announcements()->sells()->draft()->first();
        $announcement->update(['region_id' => $id]);
        if(isset($message['callback_query'])){
            $region_name = (Region::find($id)->name)??'';
            
            $message_id = $message['callback_query']['message']['message_id'];
            $chat_id = $message['callback_query']['message']['chat']['id'];
            $text = "Qaysi hududda sotasiz? : <b>$region_name</b>";
            try {
                return $this->telegram->editMessageText([
                    'chat_id' => $chat_id,
                    'message_id' => $message_id,
                    'text' => $text,
                    'parse_mode' => 'HTML',
                ]);
            } catch (\Throwable $th) {
                info($th);
            }
        }
    }
    
    public function getRegionsList()
    {
        $res = [];
        $directions = Region::all()->chunk(3);
        foreach($directions as $direction_chunk){
            $chunk = [];
            foreach ($direction_chunk as  $direction) {
                $chunk[] = Keyboard::inlineButton(['text' => $direction->name, 'callback_data' => ":direction($direction->id)"]);
            }
            $res[] = $chunk;
        }
        return $res;
    }
}