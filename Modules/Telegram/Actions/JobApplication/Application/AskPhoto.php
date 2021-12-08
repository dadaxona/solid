<?php
namespace Modules\Telegram\Actions\JobApplication\Application;

use Modules\Telegram\Actions\Action;
use Modules\Telegram\Actions\JobApplication\MenuAction;
use Modules\Telegram\Entities\JobApplication;
use Illuminate\Support\Str;
use Telegram\Bot\Keyboard\Keyboard;

class AskPhoto extends Action
{
    public static $ROUTE_NAME ='start.menu.ask-photo';
    public function getActions()
    {
        return [
            $this->translate("Bekor qilish") => MenuAction::class,
        ];
    }
    public function getNextAction($message)
    {
        return (new AskConfirmation($this->telegram, $this->telegram_chat, $this->telegram_user))->makeView($message);
    }

    
    public function makeViewParams($message)
    {
        return [
            
            'chat_id' => $this->telegram_chat->chat_id,
            'text' => $this->translate("Iltimos o‘zingizni rasmingizni jo‘nating"),
        ];
    }
    public function validateMessage($message)
    {
        $file = $this->getPhoto($message);
        if(isset($file['mime_type'])){
            return Str::contains($file['mime_type'], 'image');
        }
        return ($file != null);
    }
    public function proccessMessageData($message)
    {
        $file = $this->getPhoto($message);        
        $path = $this->downloadFile($file);
        $job_application = JobApplication::find($this->telegram_chat->job_application_id);
        if($job_application && $path)
            $job_application->update(['image' => $path]);
    }
}