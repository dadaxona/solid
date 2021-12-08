<?php
namespace Modules\Telegram\Actions;

use Modules\Telegram\Actions\JobApplication\NotFoundAction;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Action {

    protected $telegram;
    protected $translate;
    protected $telegram_chat;
    protected $telegram_user;
    public static $ROUTE_NAME = '';
    
    public function __construct($telegram, $telegram_chat, $telegram_user) {
        $this->telegram = $telegram;
        $this->telegram_chat = $telegram_chat;
        $this->telegram_user = $telegram_user;
        $this->translate = $this->translations();
    }
    public static function getMessageId($message)
    {
        if(isset($message['callback_query'])){
            return $message['callback_query']['message']['message_id'];
        }else{
            return $message['message']['message_id'];
        }
    }
    
    public static function getUser($message)
    {
        if(isset($message['callback_query'])){
            return $message['callback_query']['from'];
        }else{
            return $message['message']['from'];
        }
    }
    public static function getUserId($message)
    {
        return self::getUser($message)['id'];
    }
    public function getMessageText($message)
    {
        if(isset($message['callback_query'])){
            return $message['callback_query']['data'];
        }
        if(isset($message['message']['text'])){
            return $message['message']['text'];
        }else{
            return null;
        }
    }
    
    public function handleMessage($message)
    {
        $message_text = $this->getMessageText($message);
        if(isset($message['callback_query'])){
            $message_text = Str::between($message_text,':', '(');
        }
        $actions = $this->getActions();


        if((!$this->validateMessage($message, $this->telegram_chat)) && !isset($actions[$message_text])){
            return $this->makeView($message, $this->telegram_chat);
        }
        if(!isset($actions[$message_text])){
            $this->proccessMessageData($message, $this->telegram_chat);
        }
        try {
            return (new $actions[$message_text]($this->telegram, $this->telegram_chat, $this->telegram_user))->makeView($message);
        } catch (\Throwable $th) {
            Log::info($th);
            $this->getNextAction($message, $this->telegram_chat);
        }
    }
    
    public function getActions()
    {
        return [];
    }
    public function getNextAction($message)
    {
        return (new NotFoundAction($this->telegram, $this->telegram_chat, $this->telegram_user))->makeView($message);
    }
    public function validateMessage($message)
    {
        return true;
    }
    public function makeView($message)
    {
        $this->telegram_chat->update(['state' => $this::$ROUTE_NAME]);
        $params = $this->makeViewParams($message);
        if(isset($message['callback_query'])){
            $message_id = $message['callback_query']['message']['message_id'];
            $chat_id = $message['callback_query']['message']['chat']['id'];
            $reply_markup = $params['reply_markup']??json_encode(['inline_keyboard' => []]);
            $text = $params['text']??'';
            try {
                return $this->telegram->editMessageText([
                    'chat_id' => $chat_id,
                    'message_id' => $message_id,
                    'text' => $text,
                    'parse_mode' => 'HTML',
                    'reply_markup' => $reply_markup
                ]);
            } catch (\Throwable $th) {
                try {
                    
                    $this->telegram->editMessageReplyMarkup([
                        'chat_id' => $chat_id,
                        'message_id' => $message_id,
                        'reply_markup' => json_encode(['inline_keyboard' => []])
                    ]);
                } catch (\Throwable $th) {
                    //throw $th;
                }
            }
        }
        $this->sendTelegramMessage($params);
    }
    public function sendTelegramMessage($params){
        if(isset($params['photo'])){
            return $this->telegram->sendPhoto($params);
        } else {
            return $this->telegram->sendMessage($params);
        }
    }
    public function makeViewParams($message)
    {
        return [
            'chat_id' => $this->telegram_chat->chat_id, 
            'text' => 'Not found'
        ];
    }
    public function proccessMessageData($message)
    {
        return true;
    }
    public function getPhoto($message)
    {
        // if(isset($message['message']['document'])){
        //     return $message['message']['document'];
        // }
        if(isset($message['message']['photo'])){
            $length = count($message['message']['photo']);
            return $message['message']['photo'][$length - 1];
        }
        return null;
    }
    public function downloadFile($file)
    {
        try{
            $file_path = $this->telegram->getFile(['file_id' => $file['file_id']]);
            $url = 'https://api.telegram.org/file/bot' . $this->telegram->getAccessToken() . '/' . $file_path['file_path']; 
            $contents = file_get_contents($url);
            $name = substr($url, strrpos($url, '/') + 1);
            $path = '/job_applications/' . $name;
            Log::info(Storage::disk('public')->put($path, $contents));
            return $path;
        
        } catch (\Throwable $th) {
            return false;
            //throw $th;
        }
    }
    public function translations(){
        return [
            "uz_lat" => [],
            "uz_cyrl" => []
        ];
    }
    public function translate($text)
    {
        $language = $this->telegram_chat->language;
        return $this->translate[$language][$text]??$text;
    }
    public function middleware()
    {
        return true;
    }
}