<?php
namespace Modules\Telegram\Actions\JobApplication\Application;

use Modules\Telegram\Actions\Action;
use Modules\Telegram\Actions\JobApplication\MenuAction;
use Modules\Telegram\Entities\JobApplication;
use Telegram\Bot\Keyboard\Keyboard;

class ChooseDepartmentAction extends Action
{
    public static $ROUTE_NAME ='start.menu.choose-department';
    public function getActions()
    {
        return [
            "cancel"=> MenuAction::class,
            'sales' => SalesDepartmentAction::class
        ];
    }

    public function makeViewParams($message)
    {
        $inlineLayout = [
            [
                Keyboard::inlineButton(['text' => $this->translate("Sotuv bo'limi"), 'callback_data' => 'sales']),
            ],
            // [
            //     Keyboard::inlineButton(['text' => 'Moliya bo\'limi', 'callback_data' => 'finance'])
            // ],
            // [
            //     Keyboard::inlineButton(['text' => 'Kardlar bo\'limi', 'callback_data' => 'hr'])
            // ],
            // [
            //     Keyboard::inlineButton(['text' => 'Bugalteriya bo\'limi', 'callback_data' => 'accountance'])
            // ]
        ];

        
        return [
            'chat_id' => $this->telegram_chat->chat_id, 
            'reply_markup' => json_encode([
                'inline_keyboard' => $inlineLayout,
            ]),
            'text' => $this->translate("Maʼlumotlaringiz muvaffaqiyatli saqlandi, iltimos soha bo‘yicha yo‘nalishni tanlang")
        ];
    }
    public function proccessMessageData($message)
    {
        $message_text = $this->getMessageText($message);
        $job_application = JobApplication::find($this->telegram_chat->job_application_id);
        switch($message_text){
            case 'sales': $message_text = $this->translate("Sotuv bo'limi"); break;
        }
        if($job_application)
            $job_application->update(['department' => $message_text]);
    }
}