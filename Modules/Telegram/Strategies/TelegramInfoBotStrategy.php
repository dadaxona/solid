<?php
namespace Modules\Telegram\Strategies;

use Modules\Telegram\Actions\Action;
use Modules\Telegram\Actions\JobApplication\Application\Ask1C;
use Modules\Telegram\Actions\JobApplication\Application\AskBusinessTrip;
use Modules\Telegram\Actions\JobApplication\Application\AskChinese;
use Modules\Telegram\Actions\JobApplication\Application\AskConfirmation;
use Modules\Telegram\Actions\JobApplication\Application\AskDriveLicense;
use Modules\Telegram\Actions\JobApplication\Application\AskEnglish;
use Modules\Telegram\Actions\JobApplication\Application\AskExcel;
use Modules\Telegram\Actions\JobApplication\Application\AskFamilyPosition;
use Modules\Telegram\Actions\JobApplication\Application\AskHasAuto;
use Modules\Telegram\Actions\JobApplication\Application\AskJudgment;
use Modules\Telegram\Actions\JobApplication\Application\AskLastCompanyPosition;
use Modules\Telegram\Actions\JobApplication\Application\AskLiveTashkent;
use Modules\Telegram\Actions\JobApplication\Application\AskMilitaryService;
use Modules\Telegram\Actions\JobApplication\Application\AskNationality;
use Modules\Telegram\Actions\JobApplication\Application\AskOtherLanguage;
use Modules\Telegram\Actions\JobApplication\Application\AskOtherProgram;
use Modules\Telegram\Actions\JobApplication\Application\AskPhoto;
use Modules\Telegram\Actions\JobApplication\Application\AskRegion;
use Modules\Telegram\Actions\JobApplication\Application\AskRussian;
use Modules\Telegram\Actions\JobApplication\Application\AskSalaryExpectation;
use Modules\Telegram\Actions\JobApplication\Application\AskStudyDegree;
use Modules\Telegram\Actions\JobApplication\Application\AskTimeLimit;
use Modules\Telegram\Actions\JobApplication\Application\AskWhereKnow;
use Modules\Telegram\Actions\JobApplication\Application\AskWord;
use Modules\Telegram\Actions\JobApplication\Application\ChooseDepartmentAction;
use Modules\Telegram\Actions\JobApplication\Application\SalesDepartmentAction;
use Modules\Telegram\Actions\JobApplication\CompanyInfoAction;
use Modules\Telegram\Actions\JobApplication\CreateApplication\AskBirthDateAction;
use Modules\Telegram\Actions\JobApplication\CreateApplication\AskNameAction;
use Modules\Telegram\Actions\JobApplication\CreateApplication\AskPhoneAction;
use Modules\Telegram\Actions\JobApplication\MenuAction;
use Modules\Telegram\Actions\JobApplication\StartAction;
use Modules\Telegram\Entities\TelegramChat;
use Modules\Telegram\Services\TelegramChatService;
use Modules\Telegram\Services\TelegramUserService;
use Telegram\Bot\Api;

class TelegramInfoBotStrategy extends StrategyContract
{
    protected $actions;
    protected $telegramBot;

    public function __construct($telegram, $telegramBot) {
        $this->telegram = $telegram;
        $this->telegramBot = $telegramBot;
         $this->setActions([
            StartAction::class,
            MenuAction::class,
            CompanyInfoAction::class,
            AskNameAction::class,
            AskBirthDateAction::class,
            AskPhoneAction::class,
            ChooseDepartmentAction::class,
            SalesDepartmentAction::class,
            ChooseDepartmentAction::class,
            Ask1C::class,
            AskBusinessTrip::class,
            AskChinese::class,
            AskConfirmation::class,
            AskDriveLicense::class,
            AskEnglish::class,
            AskFamilyPosition::class,
            AskHasAuto::class,
            AskJudgment::class,
            AskLastCompanyPosition::class,
            AskLiveTashkent::class,
            AskMilitaryService::class,
            AskNationality::class,
            AskOtherLanguage::class,
            AskOtherProgram::class,
            AskPhoto::class,
            AskRegion::class,
            AskRussian::class,
            AskSalaryExpectation::class,
            AskStudyDegree::class,
            AskTimeLimit::class,
            AskWhereKnow::class,
            AskWord::class,
            AskExcel::class
        ]);
    }
    public function responseForMessage($message){
        try {
            $chat_id = $message['message']['chat']['id']??$message['callback_query']['message']['chat']['id'];
            $data = $this->getTelegramUserAndChat($chat_id, Action::getUser($message));
            $telegram_chat = $data['chat'];
            $telegram_user = $data['user'];
            $action = null;
            if($telegram_chat->state == '' || ($message['message']['text']??'') == '/start'){
                $action = $this->actions['start'];
            }else{
                $action = $this->actions[$telegram_chat['state']];
            }
    
            return (new $action($this->telegram, $telegram_chat, $telegram_user))->handleMessage($message);
        } catch (\Throwable $th) {
            info($th);
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
    static public function sendNotificationToAdminChat($message)
    {
        $chats = TelegramChat::where('is_admin_chat', true)->whereHas('telegram_bot', function($query){
            $query->where('strategy', 'hr-management');
        })->with('telegram_bot')->get();
        foreach($chats as $chat){
            $telegram = new Api($chat->telegram_bot->token);
            $telegram->sendMessage([
                'chat_id' => $chat->chat_id,
                'text' => $message,
                'parse_mode' => 'HTML',
            ]);
        }
    }
}