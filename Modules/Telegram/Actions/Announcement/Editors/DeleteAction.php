<?php
namespace Modules\Telegram\Actions\Announcement\Editors;

use Modules\Telegram\Actions\Action;
use Modules\Telegram\Actions\Announcement\MenuAction;
use Modules\Telegram\Actions\Announcement\Sell\ConfirmationAction;
use Modules\Telegram\Entities\Announcement;
use Telegram\Bot\Keyboard\Keyboard;
use Illuminate\Support\Str;

class DeleteAction extends Action
{
    public static $ROUTE_NAME = 'editors.delete';
    public function getActions()
    {
        return [
            'back' => ConfirmationAction::class
        ];
    }
    public function getNextAction($message)
    {
        return (new MenuAction($this->telegram, $this->telegram_chat, $this->telegram_user))->makeView($message);
    }
    public function makeViewParams($message)
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
        $message_text = $this->getMessageText($message);
        $id = Str::between($message_text,'(', ')');
        $inlineLayout = [
            [
                Keyboard::inlineButton(['text' => $this->translate("✅ Ha"), 'callback_data' => self::$ROUTE_NAME . ":delete($id)"]),
                Keyboard::inlineButton(['text' => $this->translate("⛔️ Yo'q"), 'callback_data' => self::$ROUTE_NAME . ":back($id)"]),
            ],
        ];
        $reply_markup = [
            'inline_keyboard' => $inlineLayout,
        ];
        return [
            'chat_id' => $this->telegram_chat->chat_id,
            'reply_markup' => json_encode($reply_markup),
            'text' => $this->translate("E'lonni o'chirmoqchimisiz: ")
        ];
    }

    public function proccessMessageData($message)
    {
        $message_text = $this->getMessageText($message);
        $id = Str::between($message_text,'(', ')');
        $action = Str::between($message_text, ':', '(');
        if($action == 'delete'){
            $announcement = null;
            if(strlen($id) == 0){
                $announcement = $this->telegram_chat->announcements()->draft()->first();
            }else{
                $announcement = Announcement::find($id);
            }
            $announcement->setDeleted()->update();
        }
    }
}