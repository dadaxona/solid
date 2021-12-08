<?php

namespace Modules\Telegram\Commands;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

class StartCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "start";

    /**
     * @var string Command Description
     */
    protected $description = "Start Command to get you started";

    /**
     * @inheritdoc
     */
    public function handle()
    {
        // This will send a message using `sendMessage` method behind the scenes to
        // the user/chat id who triggered this command.
        // `replyWith<Message|Photo|Audio|Video|Voice|Document|Sticker|Location|ChatAction>()` all the available methods are dynamically
        // handled when you replace `send<Method>` with `replyWith` and use the same parameters - except chat_id does NOT need to be included in the array.
        $keyboard = [
            ['🇺🇿 Lotincha', '🇷🇺 Кирилча']
        ];
        
        $reply_markup = [
            'keyboard' => $keyboard, 
            'resize_keyboard' => true, 
            'one_time_keyboard' => true
        ];
        $this->replyWithMessage([
            'reply_markup' => json_encode($reply_markup),
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



👧🏻 Агар сиз, инсонлар билан МУЛОҚОТ қилишни яхши кўрсангиз, СИЗ БИЗНИ САФИМИЗДАСИЗ!']);

        // This will update the chat status to typing...
        $this->replyWithChatAction(['action' => Actions::TYPING]);

        // This will prepare a list of available commands and send the user.
        // First, Get an array of all registered commands
        // They'll be in 'command-name' => 'Command Handler Class' format.
//         $commands = $this->getTelegram()->getCommands();
// 
//         // Build the list
//         $response = '';
//         foreach ($commands as $name => $command) {
//             $response .= sprintf('/%s - %s' . PHP_EOL, $name, $command->getDescription());
//         }

        // Reply with the commands list
        // $this->replyWithMessage(['text' => $response]);

        // Trigger another command dynamically from within this command
        // When you want to chain multiple commands within one or process the request further.
        // The method supports second parameter arguments which you can optionally pass, By default
        // it'll pass the same arguments that are received for this command originally.
        // $this->triggerCommand('subscribe');
    }
}