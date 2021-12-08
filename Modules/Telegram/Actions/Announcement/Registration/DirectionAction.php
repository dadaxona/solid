<?php
namespace Modules\Telegram\Actions\Announcement\Registration;

use Modules\Telegram\Actions\Action;
use Modules\Telegram\Actions\Announcement\MenuAction;
use Modules\Telegram\Entities\Direction;
use Modules\Telegram\Services\TelegramUserService;
use Modules\Telegram\Services\TelegramChatService;
use Telegram\Bot\Keyboard\Keyboard;
use Illuminate\Support\Str;


class DirectionAction extends Action
{
    public static $ROUTE_NAME ='registration.direction';
    public function getActions()
    {
        return [
            "back" => StartAction::class,
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
            // [
            //     Keyboard::inlineButton(['text' => $this->translate(" ◀️ Orqaga"), 'callback_data' => self::$ROUTE_NAME . ":back"]),
            // ]
         ];
        return [
            'chat_id' => $this->telegram_chat->chat_id,
            'reply_markup' => json_encode([
                'inline_keyboard' => $inlineLayout,
            ]),
            'text' => $this->translate("Sohangizni kiriting: ")
        ];
        
    }

    public function proccessMessageData($message)
    {
        $message_text = $this->getMessageText($message);
        $id = Str::between($message_text,'(', ')');
        $telegram_user = app()->make(TelegramUserService::class)->getOrCreate($this->telegram_chat->chat_id, Action::getUser($message));
        $telegram_user->update(['direction_id' => $id, 'registration_status' => 'completed']);
        $this->notifyAdmins($telegram_user);
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
    public function notifyAdmins($telegram_user)
    {
        $privateAdminChats = app()->make(TelegramChatService::class)->getPrivateAdminChats();
        
        info('chats');
        info($privateAdminChats);

        $message_text = "#yangifoydalanuvchi\n<strong>Yangi foydalanuvchi ro'yhatdan o'tdi</strong>\n";
        
        $region = $telegram_user->region->name??'-';
        $direction = $telegram_user->direction->name??'-';
        $message_text .= "ID: $telegram_user->id\n";
        $message_text .= "Telegram ID: <a href=\"tg://user?id=$telegram_user->user_id\">$telegram_user->user_id</a>\n";
        $message_text .= "Ism: $telegram_user->name\n";
        $message_text .= "Hudud: $region\n";
        $message_text .= "Soha: $direction\n";
        $message_text .= "Tug'ilgan sana: $telegram_user->birth_date\n";
        foreach ($privateAdminChats as $chat) {
            $this->telegram->sendMessage([
                'chat_id' => $chat->chat_id,
                'text' => $message_text,
                'parse_mode' => 'HTML',
            ]);
        }
    }
}