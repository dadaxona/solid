<?php
namespace Modules\Telegram\Strategies;

use Modules\Telegram\Actions\Action;
use Modules\Telegram\Actions\Announcement\Admin\Announcements\DeletedAction;
use Modules\Telegram\Actions\Announcement\Admin\Announcements\MenuAction as AnnouncementsMenuAction;
use Modules\Telegram\Actions\Announcement\Admin\Announcements\PublishedAction;
use Modules\Telegram\Actions\Announcement\Admin\Announcements\WaitingAction;
use Modules\Telegram\Actions\Announcement\Admin\Members\DirectionsAction as MembersDirectionsAction;
use Modules\Telegram\Actions\Announcement\Admin\Members\DownloadAction;
use Modules\Telegram\Actions\Announcement\Admin\Members\MenuAction as MembersMenuAction;
use Modules\Telegram\Actions\Announcement\Admin\Members\RegionsAction as MembersRegionsAction;
use Modules\Telegram\Actions\Announcement\Admin\MenuAction as AdminMenuAction;
use Modules\Telegram\Actions\Announcement\Admin\Settings\Direction\DirectionCreateAction as DirectionDirectionCreateAction;
use Modules\Telegram\Actions\Announcement\Admin\Settings\Direction\DirectionDeleteAction;
use Modules\Telegram\Actions\Announcement\Admin\Settings\Direction\DirectionEditAction;
use Modules\Telegram\Actions\Announcement\Admin\Settings\Direction\DirectionListAction;
use Modules\Telegram\Actions\Announcement\Admin\Settings\Direction\DirectionShowAction;
use Modules\Telegram\Actions\Announcement\Admin\Settings\IntervalAction;
use Modules\Telegram\Actions\Announcement\Admin\Settings\MenuAction as AdminSettingsMenuAction;
use Modules\Telegram\Actions\Announcement\Admin\Settings\Region\RegionCreateAction as RegionRegionCreateAction;
use Modules\Telegram\Actions\Announcement\Admin\Settings\Region\RegionDeleteAction;
use Modules\Telegram\Actions\Announcement\Admin\Settings\Region\RegionEditAction as RegionRegionEditAction;
use Modules\Telegram\Actions\Announcement\Admin\Settings\Region\RegionListAction;
use Modules\Telegram\Actions\Announcement\Admin\Settings\Region\RegionShowAction;
use Modules\Telegram\Actions\Announcement\Admin\Statistics\DirectionsAction;
use Modules\Telegram\Actions\Announcement\Admin\Statistics\RegionsAction;
use Modules\Telegram\Actions\Announcement\Admin\Statistics\StatisticsAction;
use Modules\Telegram\Actions\Announcement\Admin\Statistics\UsersAction;
use Modules\Telegram\Actions\Announcement\AnnouncementsListAction;
use Modules\Telegram\Actions\Announcement\Editors\DeleteAction;
use Modules\Telegram\Actions\Announcement\Editors\EditAction;
use Modules\Telegram\Actions\Announcement\Editors\PublishAction;
use Modules\Telegram\Actions\Announcement\MenuAction;
use Modules\Telegram\Actions\Announcement\Purchase\ConfirmationAction as PurchaseConfirmationAction;
use Modules\Telegram\Actions\Announcement\Purchase\ContactAction as PurchaseContactAction;
use Modules\Telegram\Actions\Announcement\Purchase\DescriptionAction as PurchaseDescriptionAction;
use Modules\Telegram\Actions\Announcement\Purchase\ImageAction as PurchaseImageAction;
use Modules\Telegram\Actions\Announcement\Purchase\NameAction as PurchaseNameAction;
use Modules\Telegram\Actions\Announcement\Purchase\PriceAction as PurchasePriceAction;
use Modules\Telegram\Actions\Announcement\Purchase\RegionAction as PurchaseRegionAction;
use Modules\Telegram\Actions\Announcement\Registration\BirthDateAction;
use Modules\Telegram\Actions\Announcement\Registration\DirectionAction;
use Modules\Telegram\Actions\Announcement\Registration\NameAction;
use Modules\Telegram\Actions\Announcement\Registration\RegionAction;
use Modules\Telegram\Actions\Announcement\Sell\ConfirmationAction;
use Modules\Telegram\Actions\Announcement\Sell\ContactAction;
use Modules\Telegram\Actions\Announcement\Sell\DescriptionAction;
use Modules\Telegram\Actions\Announcement\Sell\ImageAction;
use Modules\Telegram\Actions\Announcement\Sell\NameAction as SellNameAction;
use Modules\Telegram\Actions\Announcement\Sell\PriceAction;
use Modules\Telegram\Actions\Announcement\Sell\RegionAction as SellRegionAction;
use Modules\Telegram\Actions\Announcement\Settings\BirthDateAction as SettingsBirthDateAction;
use Modules\Telegram\Actions\Announcement\Settings\DirectionAction as SettingsDirectionAction;
use Modules\Telegram\Actions\Announcement\Settings\MenuAction as SettingsMenuAction;
use Modules\Telegram\Actions\Announcement\Settings\NameAction as SettingsNameAction;
use Modules\Telegram\Actions\Announcement\Settings\RegionAction as SettingsRegionAction;
use Modules\Telegram\Actions\Announcement\ShowAnnouncementAction;
use Modules\Telegram\Actions\Announcement\StartAction;
use Modules\Telegram\Entities\AnnouncementSchedule;
use Modules\Telegram\Services\TelegramChatService;
use Modules\Telegram\Services\TelegramUserService;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Telegram\Bot\Api;

class TelegramAnnouncementStraregy extends StrategyContract
{

    public function __construct($telegram, $telegramBot) {
        $this->telegram = $telegram;
        $this->telegramBot = $telegramBot;
        $this->setActions([
            // Main actions
            StartAction::class,
            MenuAction::class,
            
            //Registration
            BirthDateAction::class,
            DirectionAction::class,
            NameAction::class,
            RegionAction::class,
            
            //Sell
            ConfirmationAction::class,
            ContactAction::class,
            DescriptionAction::class,
            ImageAction::class,
            SellNameAction::class,
            PriceAction::class,
            SellRegionAction::class,
            
            //Purchase
            PurchaseConfirmationAction::class,
            PurchaseContactAction::class,
            PurchaseDescriptionAction::class,
            PurchaseImageAction::class,
            PurchaseNameAction::class,
            PurchasePriceAction::class,
            PurchaseRegionAction::class,
            
            //Settings
            SettingsMenuAction::class,
            SettingsNameAction::class,
            SettingsBirthDateAction::class,
            SettingsRegionAction::class,
            SettingsDirectionAction::class,
            
            // Editors
            PublishAction::class,
            DeleteAction::class,
            EditAction::class,


            //My announcements
            AnnouncementsListAction::class,
            ShowAnnouncementAction::class,
            
            //Admin menus
            AdminMenuAction::class,
            AdminSettingsMenuAction::class,
            StatisticsAction::class,
            AnnouncementsMenuAction::class,
            MembersMenuAction::class,
            
            //Admin settings
            IntervalAction::class,

            //Admin statistics
            DirectionsAction::class,
            RegionsAction::class,
            UsersAction::class,
            
            //Admin members
            MembersDirectionsAction::class,
            MembersRegionsAction::class,
            DownloadAction::class,
            MembersMenuAction::class,

            //Admin announcements
            PublishedAction::class,
            DeletedAction::class,
            WaitingAction::class,
             
            // Admin/Setting/Region
            RegionShowAction::class,
            RegionListAction::class,
            RegionRegionEditAction::class,
            RegionDeleteAction::class,
            RegionRegionCreateAction::class,
            
            // Admin/Setting/Direction
            DirectionShowAction::class,
            DirectionListAction::class,
            DirectionEditAction::class,
            DirectionDeleteAction::class,
            DirectionDirectionCreateAction::class
            
        ]);
    }
    public function responseForMessage($message){

        try {
            if(isset($message['my_chat_member'])){
                $this->handlePermissionChange($message);
                return true;
            }
            $chat_id = $message['message']['chat']['id']??$message['callback_query']['message']['chat']['id'];
            $data = $this->getTelegramUserAndChat($chat_id, Action::getUser($message));
            $telegram_chat = $data['chat'];
            $telegram_user = $data['user'];
            if($chat_id < 0){
                return true;
            }
            $action = null;
            if(
                $telegram_chat->state == '' ||
                ($message['message']['text']??'') == '/start' ||
                ($telegram_user->registration_status == 'pending' && !str_contains($telegram_chat->state, 'registration'))){
                $action = new $this->actions['start']($this->telegram, $telegram_chat, $telegram_user);
            } else {
                $route_name = Str::before($telegram_chat['state'],'(');
                if(isset($message['callback_query'])){
                    $query = $message['callback_query']['data'];
                    $before_query = Str::before($query,':');
                    if(isset($this->actions[$before_query])){
                        $route_name = $before_query;
                    }
                }
                $action = new $this->actions[$route_name]($this->telegram, $telegram_chat, $telegram_user);
                if($action->middleware() == false){
                    $action = (new $this->actions['menu']($this->telegram, $telegram_chat, $telegram_user));
                }
            }

            return $action->handleMessage($message);
        } catch (\Throwable $th) {
            info($th);
        }
    }
    public function handlePermissionChange($message)
    {
        $chat_id = $message['my_chat_member']['chat']['id'];
        $data = $this->getTelegramUserAndChat($chat_id, $message['my_chat_member']['from']);
        $telegram_chat = $data['chat'];
        $telegram_user = $data['user'];
        $user_id = intval($message['my_chat_member']['new_chat_member']['user']['id']);
        $token_id = intval(Str::before($this->telegramBot->token, ':'));
        if($user_id == $token_id){
            $users = $this->telegram->getChatAdministrators(['chat_id' => $chat_id]);
            $users = Arr::pluck($users, 'user');
            if($message['my_chat_member']['new_chat_member']['status'] == 'administrator'){
                foreach ($users as $user) {
                    $telegram_user = app()->make(TelegramUserService::class)->getOrCreate($chat_id,  $user);
                    $telegram_user->update(['is_admin' => true]);
                }
                
                $telegram_chat->update(['is_admin_chat' => true]);
            }else{
                foreach ($users as $user) {
                    $telegram_user = app()->make(TelegramUserService::class)->getOrCreate($chat_id,  $user);
                    $telegram_user->update(['is_admin' => false]);
                }
                $telegram_chat->update(['is_admin_chat' => false]);
            }
        }
    }
    public function getTelegramUserAndChat($chat_id, $user)
    {
        $telegram_chat = app()->make(TelegramChatService::class)->getOrCreate($chat_id, $this->telegramBot->id);
        $telegram_user = app()->make(TelegramUserService::class)->getOrCreate($chat_id,  $user);
        $telegram_chat->telegram_users()->attach($telegram_user);
        return [
            'user' => $telegram_user,
            'chat' => $telegram_chat
        ];
    }
    public static function publishWaitingAnnoucement()
    {
        $announcement_schedule = AnnouncementSchedule::waiting()->orderBy('planned_at', 'desc')->with(['announcement', 'telegram_bot'])->first();
        if($announcement_schedule){
            $announcement = $announcement_schedule->announcement;
            $telegram_bot = $announcement_schedule->telegram_bot;
            $text = self::getPreview($announcement);
            $telegram = new Api($telegram_bot->token);
            $chats = $telegram_bot->telegramChats()->publicChats()->get();
            foreach ($chats as $chat) {
                try {
                    if(!empty($announcement->image)){
                        $telegram->sendPhoto([
                            'chat_id' => $chat->chat_id,
                            'photo' => $announcement->image,
                            'parse_mode' => 'HTML',
                            'caption' => $text
                        ]);
                    }else{
                        $telegram->sendMessage([
                            'chat_id' => $chat->chat_id,
                            'parse_mode' => 'HTML',
                            'text' => $text
                        ]);
                    }
                } catch (\Throwable $th) {
                    info($th);
                }
                
            }
            $announcement_schedule->setPublished()->update();
        }
    }
    public static function getPreview($announcement)
    {
        $type = 'sotib olish';
        $type_tag = 'sotib_olish';
        if($announcement->type == 'sell'){
            $type = 'sotish';
            $type_tag = 'sotish';
        }
        $ans = [
            "Maxsulot nomi:" => $announcement->name,
            "Maxsulot narxi:" => $announcement->price,
            "Maxsulothaqida qo'shimcha ma'lumotlar:" => $announcement->description,
            "Maxsulot $type hududi:" => $announcement->region->name??'-',
            "Aloqa chiquvchi ma'lumoti:" => $announcement->contact,
        ];
        $text = "#elon #$type_tag \n";
        foreach($ans as $key => $val){
            $text .= "\n<strong>" . $key . '</strong> ' . $val;
        }
        return $text;
    }
}