<?php
namespace Modules\Telegram\Actions\JobApplication\Application;

use Modules\Telegram\Actions\Action;
use Modules\Telegram\Actions\JobApplication\MenuAction;
use Modules\Telegram\Entities\JobApplication;
use Telegram\Bot\Keyboard\Keyboard;

class AskLastCompanyPosition extends Action
{
    public static $ROUTE_NAME ='start.menu.ask-last-company-position';
    public function getActions()
    {
        return [
            "cancel" => MenuAction::class,
        ];
    }
    public function getNextAction($message)
    {
        return (new AskSalaryExpectation($this->telegram, $this->telegram_chat, $this->telegram_user))->makeView($message);
    }
    
    public function makeViewParams($message)
    {
        return [

            'chat_id' => $this->telegram_chat->chat_id,
            'text' =>$this->translate("Qaysi korxona, yoki tashkilotlarda va qaysi lavozimlarda ishlagansiz?") 
        ];
    }
    public function proccessMessageData($message)
    {
        $message_text = $this->getMessageText($message);
        $job_application = JobApplication::find($this->telegram_chat->job_application_id);
        if($job_application)
            $job_application->update(['previous_company_position' => $message_text]);
    }
}