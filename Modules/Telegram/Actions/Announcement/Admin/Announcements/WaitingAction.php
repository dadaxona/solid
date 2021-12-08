<?php
namespace Modules\Telegram\Actions\Announcement\Admin\Announcements;

use Modules\Telegram\Actions\Action;
use Modules\Telegram\Entities\Announcement;
use Telegram\Bot\Keyboard\Keyboard;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class WaitingAction extends Action
{
    public static $ROUTE_NAME ='admin.announcements.waiting';
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
        
        $announcements_count = Announcement::query()->waiting()->count();
        $message_text = $this->getMessageText($message);
        $current_page = 1;
        $limit = 5;
        $paginator = Str::between($message_text, ':', '(');
        $page = Str::between($message_text, '(', ')');
        if(is_numeric($page)){
            $current_page = intval($page);
        }
        if($paginator == 'next'){
            if($announcements_count > ($limit * $current_page)){
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
        if($announcements_count > ($limit * $current_page)){
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
            'text' => "Tasdiqlanmagan e'lonlar: $announcements_count ta"
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
        $announcements = Announcement::query()->waiting()->orderBy('created_at', 'desc')->offset(($page - 1) * $limit)->limit($limit)->get();
        foreach($announcements as $announcement){
            $keyboard[] =  [ Keyboard::inlineButton(['text' => "#$announcement->id - $announcement->name", 'callback_data' =>  self::$ROUTE_NAME . ":show($$page)"]) ];
        }
        return $keyboard;
    }
    public function middleware()
    {
        return $this->telegram_user->is_admin;
    }
}