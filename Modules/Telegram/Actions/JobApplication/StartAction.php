<?php
namespace Modules\Telegram\Actions\JobApplication;

use Modules\Telegram\Actions\Action;
use Illuminate\Support\Str;
use Telegram\Bot\Keyboard\Keyboard;

class StartAction extends Action
{
    public static $ROUTE_NAME ='start';
    public function getActions()
    {
        return ['/start' => self::class];
    }
    
    public function getNextAction($message)
    {
        return (new MenuAction($this->telegram, $this->telegram_chat, $this->telegram_user))->makeView($message);
    }
    public function makeViewParams($message)
    {
        $inlineLayout = [
            [
                Keyboard::inlineButton(['text' => $this->translate("🇺🇿 Lotincha"), 'callback_data' => self::$ROUTE_NAME . ":uz"]),
            ],
            [
                Keyboard::inlineButton(['text' => $this->translate("🇷🇺 Кирилча"), 'callback_data' => self::$ROUTE_NAME . ":ru"]),
            ],
        ];

        return [
                'chat_id' => $this->telegram_chat->chat_id,
                'parse_mode' => 'HTML',
                'reply_markup' => json_encode([
                    'inline_keyboard' => $inlineLayout,
            ]),
            'text' => 'СИЗНИ ҚАДРЛАЙДИГАН ЖАМОАДА ИШЛАШНИ ХОҲЛАЙСИЗМИ?

Унда сиз учун АЖОЙИБ ИМКОНИЯТ!

"Brendpaket" жамоаси савдо бўлимига ОПЕРАТОР-СОТУВ МЕНЕЖЕРЛИККА қизларни ишга таклиф қиламиз.

☝️ Сиздан ДИПЛОМ талаб қилинмайди!

✳️ ИШ НИМАДАН ИБОРАТ:
▪️Кириш қўнғироқларини қабул қилиш;
▪️Хушмуомилалик билан компания маҳсулотлари ҳақида маълумот бериш ва сотиш;
▪️Мижозлар маълумотларини базага киритиш;
▪️Қолдирилган заявкаларга қўнғироқ қилиш,
▪️ Сотув режаларини амалга ошириш.



✅ БИЗ, АЙНАН СИЗНИ ТАНЛАЙМИЗ, АГАР СИЗ: 
• 20-30 ёшдаги қиз бўлсангиз;
• Хушмуомилали;
• Жамоада ишлаш маҳорати;
• Маълумотларга эътиборли; 
• Масьулиятли;
• Стресс холатларига чидамли;
• Музокара маҳоратига эга;
• Рус ва Ўзбек тилларида равон сўзлаша оладиган бўлсангиз.

💻 Компьютер билимлари: MS Office (Word va Excel) дастурларида эркин фойдалана олиш.

😍 Сизни қандай ИМКОНИЯТЛАР кутмоқда БИЛАСИЗМИ?
• Бир мақсад асосида йиғилган ЖАМОА аъзоси бўлиш;
• Ойлик маошдан ташқари  БОНУСЛАРГА эга бўлиш имконияти;
• замонавий шинам офисда ишлаш;
• Ўз вактида ойлик маош + Сотувлардан бонуслар олиш 
• Расман ишга кабул қилиш;
• БЕПУЛ ўқиш ва Тажриба олиш имконияти;
• Шахсий ривожланиш ва Карера қилиш;
• Иш вакти: 8:30 - 18:00 гача. Хафтада 6/1 якшанба дам олиш куни.



👧🏻 Агар сиз, инсонлар билан МУЛОҚОТ қилишни яхши кўрсангиз, СИЗ БИЗНИ САФИМИЗДАСИЗ!'];

    }
    
    public function proccessMessageData($message)
    {
        $message_text = $this->getMessageText($message);
        $lang = 'uz_cyrl';
        if(Str::contains($message_text, 'Lotincha')) $lang = 'uz_lat'; 
        if($lang){
            $this->telegram_chat->update(['language' => $lang]);
        } 
    }
}