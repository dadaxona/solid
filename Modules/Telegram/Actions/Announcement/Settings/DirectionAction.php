<?php
namespace Modules\Telegram\Actions\Announcement\Settings;

use Modules\Telegram\Actions\Action;
use Modules\Telegram\Entities\Direction;
use Modules\Telegram\Services\TelegramUserService;
use Telegram\Bot\Keyboard\Keyboard;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class DirectionAction extends Action
{
    public static $ROUTE_NAME ='settings.update.direction';
    public function getActions()
    {
        return [
            'cancel' => MenuAction::class,
        ];
    }
    public function getNextAction($message)
    {
        return (new MenuAction($this->telegram, $this->telegram_chat, $this->telegram_user))->makeView($message);
    }

    
    public function makeViewParams($message)
    {
        $inlineLayout = [
            ...$this->getDirectionsList(),
            [
                Keyboard::inlineButton(['text' => $this->translate("🚫 Bekor qilish"), 'callback_data' => self::$ROUTE_NAME . ":cancel"]),
            ],
        ];
        $telegram_user = app()->make(TelegramUserService::class)->getOrCreate($this->telegram_chat->chat_id, Action::getUser($message));
        $direction_name = '-';
        if($telegram_user->direction){
            $direction_name = $telegram_user->direction->name;
        }
        return [
            'chat_id' => $this->telegram_chat->chat_id,
            'parse_mode' => 'HTML',
            'reply_markup' => json_encode([
                'inline_keyboard' => $inlineLayout,
            ]),
            'text' => "Soha(<strong>$direction_name</strong>):"
        ];
    }
    public function validateMessage($message)
    {
        $message_text = $this->getMessageText($message);
        $rules = ['message_text' => ['string']];

        $input = ['message_text' => $message_text];

        return Validator::make($input, $rules)->passes();
    }
    public function proccessMessageData($message)
    {
        $message_text = $this->getMessageText($message);
        $id = Str::between($message_text,'(', ')');
        $telegram_user = app()->make(TelegramUserService::class)->getOrCreate($this->telegram_chat->chat_id, Action::getUser($message));
        $telegram_user->update(['direction_id' => $id, 'registration_status' => 'completed']);
    }
    
    public function getDirectionsList()
    {
        $res = [];
        $directions = Direction::all()->chunk(3);
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