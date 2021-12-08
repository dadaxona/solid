<?php
namespace Modules\Telegram\Services;

use Modules\Core\Services\ServiceContract;
use Modules\Telegram\Entities\TelegramBot;

class TelegramBotService extends ServiceContract
{

    public function __construct(protected TelegramBot $telegram_bot)
    {
        $this->model = $telegram_bot;
    }
    
    public function create($data){
        
        $telegram_bot = $this->model->create($data);
        
        return $telegram_bot;
    }

    public function update($data)
    {
        $telegram_bot = $this->model->find($data['id']);
        $telegram_bot->update($data);
        return $telegram_bot;
    }
    public function getByStrategy($strategy)
    {
        return $this->model->where('strategy', $strategy)->first();
    }
}