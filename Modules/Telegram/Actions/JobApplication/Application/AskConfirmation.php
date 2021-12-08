<?php
namespace Modules\Telegram\Actions\JobApplication\Application;

use Modules\Telegram\Actions\Action;
use Modules\Telegram\Actions\JobApplication\MenuAction as JobApplicationMenuAction;
use Modules\Telegram\Entities\JobApplication;
use Modules\Telegram\Strategies\TelegramAdminBotStrategy;
use Telegram\Bot\Keyboard\Keyboard;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Modules\Telegram\Strategies\TelegramInfoBotStrategy;

class AskConfirmation extends Action
{
    public static $ROUTE_NAME ='start.menu.ask-confimation';
    public function getActions()
    {
        return [
            "cancel" => JobApplicationMenuAction::class,
        ];
    }
    public function getNextAction($message)
    {
        return (new JobApplicationMenuAction($this->telegram, $this->telegram_chat, $this->telegram_user))->makeView($message);
    }

    
    public function makeViewParams($message)
    {
        $inlineLayout = [
            [
                Keyboard::inlineButton(['text' => $this->translate("Yuborish"), 'callback_data' => "accept"]),
                Keyboard::inlineButton(['text' => $this->translate("Bekor qilish"), 'callback_data' => "ignore"]),
            ],
        ];
        
        $job_application = JobApplication::find($this->telegram_chat->job_application_id);
        
        // keylar tarjima
        $ans = [
            $this->translate("Ism,familiyangiz:") => $job_application->full_name,
            $this->translate("Tug‘ilgan kun,oy,yilingiz:") => $job_application->birth_date,
            $this->translate("Telefon raqamingiz:") => $job_application->phone_number,
            $this->translate("Soha bo‘yicha yo‘nalishni:") => $job_application->department,
            $this->translate("Viloyatingiz:") => $job_application->region,
            $this->translate("Millatingiz:") => $job_application->nationality,
            $this->translate("Oilaviy ahvolingiz:") => $job_application->family_position,
            $this->translate("Qaysi korxona, yoki tashkilotlarda va qaysi lavozimlarda ishlagansiz:") => $job_application->previous_company_position,
            $this->translate("Bizda qancha oylikka ishlamoqchisiz:") => $job_application->salary_expectation,
            $this->translate("Bizning korxonada qancha muddat ishlamoqchisiz:") => $job_application->time_limit,
            $this->translate("Korxona tamonidan xizmat safariga chiqishga rozimisiz:") => $job_application->business_trip,
            $this->translate("Xarbiy xizmatga borganmisiz:") => $job_application->military_service,
            $this->translate("Sudlanganmisiz:") => $job_application->judgment,
            $this->translate("Xaydovchilik guvoxnomangiz bormi:") => $job_application->driver_license,
            $this->translate("O‘zingizning shaxsiy avtomobilingiz bormi:") => $job_application->has_auto,
            $this->translate("Rus tilini bilishingiz darajasi:") => $job_application->russian_level,
            $this->translate("Ingliz tilini bilishingiz darajasi:") => $job_application->english_level,
            $this->translate("Xitoy tilini bilishingiz darajasi:") => $job_application->chinese_level,
            $this->translate("Boshqa til:") => $job_application->other_language_level,
            $this->translate("Word dasturini bilishingiz darajasi:") => $job_application->word_level,
            $this->translate("Excel dasturini bilishingiz darajasi:") => $job_application->excel_level,
            $this->translate("Boshqa qanday dasturni bilasiz:") => $job_application->other_program_level,
            $this->translate("Korxonamiz haqida qayerdan ma'lumot oldingiz:") => $job_application->where_know,
        ];
        // tarjima
        $text = $this->translate("Sizning maʼlumotlaringiz");
        foreach($ans as $key => $val){
            $text .= "\n<strong>" . $key . '</strong> ' . $val;
        }
        return [
            'chat_id' => $this->telegram_chat->chat_id,
            'parse_mode' => 'HTML',
            'reply_markup' => json_encode([
                'inline_keyboard' => $inlineLayout,
            ]),
            'text' => $text
        ];
    }
    public function validateMessage($message)
    {
        $message_text = $this->getMessageText($message);
        $rules = ['message_text' => ['string',  Rule::in(["accept", "ignore", $this->translate("Bekor qilish"), $this->translate("Bekor qilish")]),]];

        $input = ['message_text' => $message_text];

        return Validator::make($input, $rules)->passes();
    }
    public function proccessMessageData($message)
    {
        $message_text = $this->getMessageText($message);
        $job_application = JobApplication::find($this->telegram_chat->job_application_id);
        if($message_text == 'accept') {
            $message_text = 'confirmed';
            TelegramInfoBotStrategy::sendNotificationToAdminChat($this->getApplicationPreview($job_application));
        } else $message_text = 'not_confirmed';
        if($job_application)
            $job_application->update(['status' => $message_text]);
    }
    public function getApplicationPreview($job_application)
    {
        // keylar tarjima
        $ans = [
            'Ism,familiyangiz:' => $job_application->full_name,
            'Tug‘ilgan kun,oy,yilingiz:' => $job_application->birth_date,
            'Telefon raqamingiz:' => $job_application->phone_number,
            'Soha bo‘yicha yo‘nalishni:' => $job_application->department,
            'Viloyatingiz:' => $job_application->region,
            'Millatingiz:' => $job_application->nationality,
            'Oilaviy ahvolingiz:' => $job_application->family_position,
            'Qaysi korxona, yoki tashkilotlarda va qaysi lavozimlarda ishlagansiz:' => $job_application->previous_company_position,
            'Bizda qancha oylikka ishlamoxchisiz:' => $job_application->salary_expectation,
            'Bizning korxonada qancha muddat ishlamoxchisiz:' => $job_application->time_limit,
            'Korxona tamonidan xizmat safariga chiqishga rozimisiz:' => $job_application->business_trip,
            'Xarbiy xizmatga borganmisiz:' => $job_application->military_service,
            'Sudlanganmisiz:' => $job_application->judgment,
            'Xaydovchilik guvoxnomangiz bormi:' => $job_application->driver_license,
            'O‘zingizning shaxsiy avtomobilingiz bormi:' => $job_application->has_auto,
            'Rus tilini bilishingiz darajasi:' => $job_application->russian_level,
            'Ingliz tilini bilishingiz darajasi:' => $job_application->english_level,
            'Xitoy tilini bilishingiz darajasi:' => $job_application->chinese_level,
            'Boshqa til:' => $job_application->other_language_level,
            'Word dasturini bilishingiz darajasi:' => $job_application->word_level,
            'Excel dasturini bilishingiz darajasi:' => $job_application->excel_level,
            'Boshqa kanday dasturni bilasiz:' => $job_application->other_program_level,
            'Korxonamiz xaqida qayerdan malumot oldingiz:' => $job_application->where_know,
        ];
        // tarjima
        $text = 'Sizning maʼlumotlaringiz';
        foreach($ans as $key => $val){
            $text .= "\n<strong>" . $key . '</strong> ' . $val;
        }
        return $text;
    }
}