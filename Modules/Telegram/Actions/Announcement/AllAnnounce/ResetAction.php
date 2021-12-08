<?php
namespace Modules\Telegram\Actions\Announcement\AllAnnounce;

use Modules\Telegram\Actions\Action;
use Modules\Telegram\Actions\Announcement\MenuAction;
use Telegram\Bot\Keyboard\Keyboard;


class ResetAction extends Action
{
    public static $ROUTE_NAME = 'all_announce.create.reset';
    public function getActions()
    {
        
        return [
            "back" => AnnounceHistoryAction::class,
        ];
    }
    public function getNextAction($message)
    {
        return (new MenuAction($this->telegram, $this->telegram_chat, $this->telegram_user))->makeView($message);
    }
    public function makeViewParams($message)
    {
         $inlineLayout = [
        [
            Keyboard::inlineButton(['text' => $this->translate(" ◀️ Orqaga"), 'callback_data' => self::$ROUTE_NAME . ":back"]),
        ]
    ];
    return [
        'chat_id' => $this->telegram_chat->chat_id,
        'reply_markup' => json_encode([
            'inline_keyboard' => $inlineLayout,
        ]),
            'text' => $this->translate("E'lonni qayta joylamoqchimisiz: ")
            ];
    }

    public function proccessMessageData($message)
    {
        $message_text = $this->getMessageText($message);
        // $job_application = JobApplication::find($this->telegram_chat->job_application_id);
        // if($job_application)
        //     $job_application->update(['birth_date' => $message_text]);
    }
}