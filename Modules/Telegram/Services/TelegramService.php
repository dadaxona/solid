<?php
namespace Modules\Telegram\Services;

use Modules\Core\Services\ServiceContract;
use Modules\Telegram\Entities\TelegramBot;
use Modules\Telegram\Strategies\TelegramAnnouncementStraregy;
use Modules\Telegram\Strategies\TelegramInfoBotStrategy;
use Telegram\Bot\Api;

class TelegramService extends ServiceContract
{
    protected $telegram;
    protected $telegramBot;

    public function setTelegramBot(TelegramBot $telegramBot)
    {
        $this->telegram = new Api($telegramBot->token);
        $this->telegramBot = $telegramBot;
    }
    
    public function setWebhook()
    {
        return $this->telegram->setwebhook(['url' => route('telegram.webhook.handler', ['token' => $this->telegramBot->token])]);
    }
    
    public function removeWebhook()
    {
        return $this->telegram->removewebhook();
    }
    public function getUpdates()
    {
        return $this->telegram->getUpdates();
    }
    
    public function responseForMessage($message)
    {
        $strategy = null;
        switch ($this->telegramBot->strategy) {
            case 'hr-management':
                $strategy = new TelegramInfoBotStrategy($this->telegram, $this->telegramBot);
                break;
            case 'announcement':
                $strategy = new TelegramAnnouncementStraregy($this->telegram, $this->telegramBot);
            default:
                # code...
                break;
        }
        return $strategy->responseForMessage($message);
    }
}