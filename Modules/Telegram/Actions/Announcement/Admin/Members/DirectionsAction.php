<?php
namespace Modules\Telegram\Actions\Announcement\Admin\Members;

use Modules\Telegram\Actions\Action;
use Modules\Telegram\Entities\Direction;
use Telegram\Bot\Keyboard\Keyboard;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class DirectionsAction extends Action
{
    public static $ROUTE_NAME ='admin.members.directions';
    public function getActions()
    {
        return [
            'show' => self::class,
            'back' => MenuAction::class,
            'before'=> self::class,
            'next'=> self::class,
        ];
    }
    public function getNextAction($message)
    {
        return (new MenuAction($this->telegram, $this->telegram_chat, $this->telegram_user))->makeView($message);
    }

    public function makeViewParams($message)
    {
        
        $direction_count = Direction::query()->count();
        $message_text = $this->getMessageText($message);
        $current_page = 1;
        $limit = 5;
        $paginator = Str::between($message_text, ':', '(');
        $page = Str::between($message_text, '(', ')');
        if(is_numeric($page)){
            $current_page = intval($page);
        }
        if($paginator == 'next'){
            if($direction_count > ($limit * $current_page)){
                $current_page++;
            }
        }
        if($paginator == 'before'){
            if($current_page > 1){
                $current_page--;
            }
        }
        $inlineLayout = $this->getAnnouncementsKeyboard($current_page, $limit);
        $paginator = [];
        if($current_page > 1){
            $paginator[] = Keyboard::inlineButton(['text' => $this->translate("⬅️ Avvalgi"), 'callback_data' => self::$ROUTE_NAME . ":before($current_page)"]);
        }
        if($direction_count > ($limit * $current_page)){
            $paginator[] = Keyboard::inlineButton(['text' => $this->translate("➡️ Kegingi"), 'callback_data' => self::$ROUTE_NAME . ":next($current_page)"]);
        }
        $inlineLayout[] = $paginator;
        $inlineLayout[] = [
            Keyboard::inlineButton(['text' => $this->translate(" ◀️ Orqaga"), 'callback_data' => self::$ROUTE_NAME . ":back"]),
        ];
        
        return [
            'chat_id' => $this->telegram_chat->chat_id,
            'parse_mode' => 'HTML',
            'reply_markup' => json_encode([
                'inline_keyboard' => $inlineLayout,
            ]),
            'text' => "Sohalar"
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
        
    }
    public function getAnnouncementsKeyboard($page = 1, $limit=5)
    {
        $keyboard = [];
        $directions = Direction::withCount('telegram_users')->orderBy('telegram_users_count')->offset(($page - 1) * $limit)->limit($limit)->get();

        foreach($directions as $direction){
            $keyboard[] =  [Keyboard::inlineButton(['text' => "#$direction->id - $direction->name: $direction->telegram_users_count ta", 'callback_data' =>  self::$ROUTE_NAME . ":show($$page)"])];
        }
        return $keyboard;
    }
    public function middleware()
    {
        return $this->telegram_user->is_admin;
    }
}