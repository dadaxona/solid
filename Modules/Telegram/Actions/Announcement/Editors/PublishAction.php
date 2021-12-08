<?php
namespace Modules\Telegram\Actions\Announcement\Editors;

use Modules\Telegram\Actions\Action;
use Modules\Telegram\Actions\Announcement\MenuAction;
use Modules\Telegram\Actions\Announcement\ShowAnnouncementAction;
use Modules\Telegram\Entities\Announcement;
use Modules\Telegram\Entities\AnnouncementSchedule;
use Telegram\Bot\Keyboard\Keyboard;
use Illuminate\Support\Str;
use PDO;

class PublishAction extends Action
{
    public static $ROUTE_NAME = 'editors.publish';
    public function getActions()
    {
        return [
            'back' => ShowAnnouncementAction::class 
        ];
    }
    public function getNextAction($message)
    {
        return (new MenuAction($this->telegram, $this->telegram_chat, $this->telegram_user))->makeView($message);
    }
    public function makeView($message)
    {
        if(isset($message['callback_query'])){
            $message_id = $message['callback_query']['message']['message_id'];
            $chat_id = $message['callback_query']['message']['chat']['id'];
            $reply_markup = $params['reply_markup']??json_encode([]);
            $this->telegram->editMessageReplyMarkup([
                'chat_id' => $chat_id,
                'message_id' => $message_id,
                'reply_markup' => json_encode([
                    'inline_keyboard' => [],
                ]),
            ]);
        }
        $this->telegram_chat->update(['state' => $this::$ROUTE_NAME]);
        if($this->canPublishNew()){
            return $this->telegram->sendMessage($this->makeViewParams($message));
        }else{
            $inlineLayout = [
                [
                    Keyboard::inlineButton(['text' => $this->translate("Asosiy Menyuga qaytish"), 'callback_data' => self::$ROUTE_NAME . ":back"]),
                ],
            ];
            $reply_markup = [
                'inline_keyboard' => $inlineLayout,
            ];
            return $this->telegram->sendMessage([
                'chat_id' => $this->telegram_chat->chat_id,
                'text' => 'Kunlik limitingiz tugagan. Iltimos keginroq urinib ko\'ring',
                'reply_markup' => json_encode($reply_markup),
            ]);
        }
    }
    public function makeViewParams($message)
    {
        $message_text = $this->getMessageText($message);
        $id = Str::between($message_text,'(', ')');
        
        $inlineLayout = [
            [
                Keyboard::inlineButton(['text' => $this->translate("✅ Ha"), 'callback_data' => self::$ROUTE_NAME . ":publish($id)"]),
                Keyboard::inlineButton(['text' => $this->translate("⛔️ Yo'q"), 'callback_data' => self::$ROUTE_NAME . ":back($id)"]),
            ],
        ];
        $reply_markup = [
            'inline_keyboard' => $inlineLayout,
        ];
        return [
            'chat_id' => $this->telegram_chat->chat_id,
            'reply_markup' => json_encode($reply_markup),
            'text' => $this->translate("E'lon berasizmi? ")
        ];
    }

    public function proccessMessageData($message)
    {
        $message_text = $this->getMessageText($message);
        $action = Str::between($message_text, ':', '(');
        $id = Str::between($message_text, '(', ')');
        if($action == 'publish'){
            $announcement = null;
            if(strlen($id) == 0){
                $announcement = $this->telegram_chat->announcements()->draft()->first();
            }else{
                $announcement = Announcement::find($id);
            }
            $announcement_schedule = AnnouncementSchedule::create([
                'telegram_bot_id' => $this->telegram_chat->telegram_bot_id,
                'announcement_id' => $announcement->id,
                'status' => 'requested',
                'planned_at' => now(),
            ]);
            $announcement->setWaiting()->update();
            $this->sendRequestToAdminChats($announcement, $announcement_schedule);
            $this->telegram->sendMessage([
                'chat_id' => $this->telegram_chat->chat_id,
                'text' => "Sizning e'loningiz tasdiqlash uchun administratorlarga jo'natildi!\nIltimos biroz kutib turing!" ,
            ]);
        }
        if($action == 'public_publish' || $action == 'ignore_publish'){
            $announcement_schedule = AnnouncementSchedule::find($id);
            
            if($announcement_schedule->status == 'requested'){
                if($action == 'public_publish'){
                    $announcement_schedule->setWaiting()->update();
                }else{
                    $announcement_schedule->setIgnored()->update();
                }
            } else {
                $state = 'bekor qilingan';
                if($announcement_schedule->status == 'waiting'){
                    $state = 'tasdiqlangan';
                }
                $this->telegram->sendMessage([
                    'chat_id' => $this->telegram_chat->chat_id,
                    'text' => "Ushbu e'lon $state!",
                ]);
            }
        }
    }
    public function sendRequestToAdminChats($announcement, $schedule){
        $chats = $this->telegram_chat->telegram_bot->telegramChats()->whereHas('telegram_users', function ($query) {
            $query->where('is_admin', true);
        })->privateChats()->get();
        $message_text = $this->getPreview($announcement, $schedule);
        $id = $schedule->id;
        $inlineLayout = [
            [
                Keyboard::inlineButton(['text' => $this->translate("✔️ Tasdiqlash"), 'callback_data' => self::$ROUTE_NAME . ":public_publish($id)"]),
                Keyboard::inlineButton(['text' => $this->translate("🚫 Bekor qilish"), 'callback_data' => self::$ROUTE_NAME . ":ignore_publish($id)"]),
            ],
        ];
        $reply_markup = [
            'inline_keyboard' => $inlineLayout,
        ];
        foreach($chats as $chat){
            $params = [
                'chat_id' => $chat->chat_id,
                'parse_mode' => 'HTML',
                'reply_markup' => json_encode($reply_markup),
            ];
            if(!empty($announcement->image)){
                $params['caption'] = $message_text;
                $params['photo'] = $announcement->image;

            }else{
                $params['text'] = $message_text;
            }
            $this->sendTelegramMessage($params);
        }
    }
    public function canPublishNew()
    {
        $telegram_chat = $this->telegram_chat;
        $telegram_bot = $this->telegram_chat->telegram_bot;
        
        $announcement = AnnouncementSchedule::whereIn('status', ['waiting', 'published'])
            ->whereHas('announcement', function($query) use($telegram_chat){
                $query->where('announcements.telegram_chat_id', $telegram_chat->id);
            })
            ->orderBy('planned_at', 'desc')->first();
        if($announcement){
            $planned_at = $announcement->planned_at;
            $difference = now()->diffInDays($planned_at);
            $limit = $telegram_bot->settings['interval'];
            if($difference < $limit){
                return false;
            }
        }
        return true;
    }
    public function getPreview($announcement)
    {
        $type = 'sotib olish';
        $type_tag = 'sotib_olish';
        if($announcement->type == 'sell'){
            $type = 'sotish';
            $type_tag = 'sotish';
        }
        $ans = [
            $this->translate("Maxsulot nomi:") => $announcement->name,
            $this->translate("Maxsulot narxi:") => $announcement->price,
            $this->translate("Maxsulothaqida qo'shimcha ma'lumotlar:") => $announcement->description,
            $this->translate("Maxsulot $type hududi:") => $announcement->region->name??'-',
            $this->translate("Aloqa chiquvchi ma'lumoti:") => $announcement->contact,
        ];
        $text = $this->translate("<strong>Quyidagi e'lonni tasdiqlaysizmi?</strong> \n");
        $text .= $this->translate("#elon #$type_tag \n");
        foreach($ans as $key => $val){
            $text .= "\n<strong>" . $key . '</strong> ' . $val;
        }
        return $text;
    }
}