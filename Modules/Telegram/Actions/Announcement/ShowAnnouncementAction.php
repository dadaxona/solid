<?php
namespace Modules\Telegram\Actions\Announcement;

use Modules\Telegram\Actions\Action;
use Modules\Telegram\Actions\Announcement\Editors\DeleteAction;
use Modules\Telegram\Actions\Announcement\Editors\EditAction;
use Modules\Telegram\Actions\Announcement\Editors\PublishAction;
use Telegram\Bot\Keyboard\Keyboard;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ShowAnnouncementAction extends Action
{
    public static $ROUTE_NAME ='show-announcement';
    public function getActions()  
    {
        return [
            'back' => MenuAction::class,
            'edit' => EditAction::class,
            'delete' => DeleteAction::class,
            'publish' => PublishAction::class
        ];
    }
    public function getNextAction($message)
    {
        return (new MenuAction($this->telegram, $this->telegram_chat, $this->telegram_user))->makeView($message);
    }

    public function makeViewParams($message)
    { 
        $message_text = $this->getMessageText($message);
        $id = Str::between($message_text,'(', ')');
        $announcement = $this->telegram_chat->announcements()->where('id', $id)->first();
        $inlineLayout = [
            [
                Keyboard::inlineButton(['text' => $this->translate("📃 E'lon berish"), 'callback_data' => self::$ROUTE_NAME . ":publish($id)"]),
            ],
            // [ 
            //     Keyboard::inlineButton(['text' => $this->translate("E'lonni tahrirlash"), 'callback_data' => self::$ROUTE_NAME . ":edit($id)"]),
            // ],
            [
                Keyboard::inlineButton(['text' => $this->translate("⛔️ E'lonni o'chirish"), 'callback_data' => self::$ROUTE_NAME . ":delete($id)"]),
            ],
            [
                Keyboard::inlineButton(['text' => $this->translate(" ◀️ Orqaga"), 'callback_data' => self::$ROUTE_NAME . ":back($id)"]),
            ]
        ];
        $reply_markup = [
            'inline_keyboard' => $inlineLayout,
        ];
        
        return [
            'photo' => $announcement->image,
            'chat_id' => $this->telegram_chat->chat_id,
            'parse_mode' => 'HTML',
            'reply_markup' => json_encode($reply_markup),
            'caption' => $this->getPreview($announcement)
        ];
    }
    public function validateMessage($message)
    {
        $message_text = $this->getMessageText($message);
        $rules = ['message_text' => ['string',  Rule::in(["publish", "edit", "delete","back"]),]];

        $input = ['message_text' => $message_text];

        return Validator::make($input, $rules)->passes();
    }
    public function proccessMessageData($message)
    {
        $message_text = $this->getMessageText($message);

        info($message_text);
    }
    public function getPreview($announcement)
    {
        
        $ans = [
            $this->translate("Maxsulot nomi:") => $announcement->name,
            $this->translate("Maxsulot narxi:") => $announcement->price,
            $this->translate("Maxsulothaqida qo'shimcha ma'lumotlar:") => $announcement->description,
            $this->translate("Maxsulot sotish hududi:") => $announcement->region->name ?? '-',
            $this->translate("Aloqa chiquvchi ma'lumoti:") => $announcement->contact,
        ];
        
        $text = $this->translate("#elon #sotish \n");
        foreach($ans as $key => $val){
            $text .= "\n<strong>" . $key . '</strong> ' . $val;
        }
        return $text;
    }
}