<?php
namespace Modules\Telegram\Actions\JobApplication;

use Modules\Telegram\Actions\Action;
use Telegram\Bot\Keyboard\Keyboard;

class CompanyInfoAction extends Action
{
    public static $ROUTE_NAME ='start.menu.company-info';
    public function getActions()
    {
        return [
            "back" => MenuAction::class,
        ];
    }
    public function makeViewParams($message)
    {
            $inlineLayout = [
                [
                    Keyboard::inlineButton(['text' => $this->translate(" ◀️ Orqaga"), 'callback_data' => self::$ROUTE_NAME . ":back"]),
                ],
            ];

            return [
                'chat_id' => $this->telegram_chat->chat_id,
                'parse_mode' => 'HTML',
                'reply_markup' => json_encode([
                    'inline_keyboard' => $inlineLayout,
                ]),
            'text' => $this->translate("“BRENDPAKET” компанияси, 2018-йилдан буён, Тошкент шаҳрида пакет ва упаковкалар ишлаб чиқариш сохасида, OEM котракт усулида ўз фаолиятини юргазиб келмоқда.

Компания мақсади Жамиятимиздаги тадбиркорларнинг ривожланишига ўз ҳиссасини қўшиш, ҳамда уларга қулайлик ва имконият яратиш орқали халқимизни ҳаётини яхшилаш.")
        ];
    }
}