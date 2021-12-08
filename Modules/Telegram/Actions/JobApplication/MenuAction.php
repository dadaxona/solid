<?php
namespace Modules\Telegram\Actions\JobApplication;

use Modules\Telegram\Actions\Action;
use Modules\Telegram\Actions\CreateApplication\AskNameAction;
use Modules\Telegram\Actions\JobApplication\CreateApplication\AskNameAction as CreateApplicationAskNameAction;
use Telegram\Bot\Keyboard\Keyboard;

class MenuAction extends Action
{
    public static $ROUTE_NAME ='start.menu';
    public function getActions()
    {
        return [
            "about" => CompanyInfoAction::class,
            "registration" => AskNameAction::class,
            "back" => StartAction::class,
        ];
    }
    public function getNextAction($message)
    {
        return (new CreateApplicationAskNameAction($this->telegram, $this->telegram_chat, $this->telegram_user))->makeView($message);
    }
    
    public function makeViewParams($message)
    {
         
        $inlineLayout = [
            [
                Keyboard::inlineButton(['text' => $this->translate("Ro'yxatdan o'tish"), 'callback_data' => self::$ROUTE_NAME . ":registration"]),
            ],
            [  
                Keyboard::inlineButton(['text' => $this->translate("Korxona haqida to'liq ma'lumot"), 'callback_data' => self::$ROUTE_NAME . ":about"]),
            ],
            [
                Keyboard::inlineButton(['text' => $this->translate(" ◀️ Orqaga"), 'callback_data' => self::$ROUTE_NAME . ":back"]),
            ],
        ];
        
        $response_text = $this->translate("Asosiy bo'limga xush keldingiz!");
        
        return [
            'chat_id' => $this->telegram_chat->chat_id,
            'parse_mode' => 'HTML',
            'reply_markup' => json_encode([
                'inline_keyboard' => $inlineLayout,
            ]),

            'text' => $response_text
        ];
    }
}