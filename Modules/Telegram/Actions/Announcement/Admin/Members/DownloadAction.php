<?php
namespace Modules\Telegram\Actions\Announcement\Admin\Members;

use Modules\Telegram\Actions\Action;
use Telegram\Bot\FileUpload\InputFile;
use Telegram\Bot\Keyboard\Keyboard;

class DownloadAction extends Action
{
    public static $ROUTE_NAME = 'admin.members.download';
    public function getActions()
    {
        return [
            "back" => MenuAction::class,
        ];
    }
    public function getNextAction($message)
    {
        return (new MenuAction($this->telegram, $this->telegram_chat, $this->telegram_user))->makeView($message);
    }

    public function makeView($message)
    {
        $this->telegram_chat->update(['state' => $this::$ROUTE_NAME]);

        return $this->telegram->sendDocument($this->makeViewParams($message));
    }
    public function makeViewParams($message)
    {
        
        $inlineLayout = [
            [
                Keyboard::inlineButton(['text' => $this->translate(" ◀️ Orqaga"), 'callback_data' => self::$ROUTE_NAME . ":back"]),
            ],
        ];
        $keyboard = [
            'inline_keyboard' => $inlineLayout,
        ];
        
        return [
            'chat_id' => $this->telegram_chat->chat_id,
            'document' =>  new InputFile(route('telegram-users.export', ['file_name' => 'telegram_users.xlsx']), 'Foydalanuvchilar-' . now() . '.xlsx'),// ,
            'parse_mode' => 'HTML',
            'reply_markup' => json_encode($keyboard),
            'text' => "Foydalanuvchi ma'lumotlari"
        ];
    }
    public function proccessMessageData($message)
    {
        $message_text = $this->getMessageText($message);
    }
    public function middleware()
    {
        return $this->telegram_user->is_admin;
    }
}