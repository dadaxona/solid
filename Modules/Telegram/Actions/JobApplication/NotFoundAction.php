<?php
namespace Modules\Telegram\Actions\JobApplication;

use Modules\Telegram\Actions\Action;

class NotFoundAction extends Action
{
    public function makeViewParams($message)
    {
        $response_text = $this->translate("Buyruq topilmadi! Qayta urinib ko'ring!\n Qayta boshlash uchun /start buyrog'ini bosing!");
        
        return [
            'chat_id' => $this->telegram_chat->chat_id,
            'text' => $response_text
        ];
    }
}