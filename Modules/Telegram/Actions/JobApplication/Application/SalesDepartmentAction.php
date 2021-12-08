<?php
namespace Modules\Telegram\Actions\JobApplication\Application;

use Modules\Telegram\Actions\Action;
use Modules\Telegram\Actions\JobApplication\MenuAction;
use Telegram\Bot\Keyboard\Keyboard;

class SalesDepartmentAction extends Action
{
    public static $ROUTE_NAME ='start.menu.sales-department';
    public function getActions()
    {
        return [
            'back' => ChooseDepartmentAction::class,
            'ask_region' => AskRegion::class,
            'cancel' => MenuAction::class
        ];
    }

    
    public function makeViewParams($message)
    {
        $inlineLayout = [
            [
                Keyboard::inlineButton(['text' => $this->translate("Anketa to'ldirish"), 'callback_data' => 'ask_region']),
            ],
            [
                Keyboard::inlineButton(['text' => $this->translate("🔙 Орқага"), 'callback_data' => $this::$ROUTE_NAME . ':back'])
            ]
        ];

        return [
            'chat_id' => $this->telegram_chat->chat_id, 
            'reply_markup' => json_encode([
                'inline_keyboard' => $inlineLayout,
            ]),
            'text' => $this->translate("Siz tanlagan soha xodimlari yuqorida ko‘rsatilgan talablarga javob berishi kerak.\nTanishib chiqqaningizdan so‘ng anketa to‘ldirishingiz mumkin")
        ];
    }
}