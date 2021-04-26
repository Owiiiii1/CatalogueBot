<?php


namespace App\Conversations;


use App\KeyboardMakers\TelegramKeyboardMaker;
use App\MessageMakers\TelegramMessageMaker;
use App\Models\BotUser;
use App\Models\Buttons;
use App\Models\MainMenu;
use App\Models\Manager;
use App\Models\Region;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\Drivers\Telegram\TelegramDriver;

class NewQuestionConversation extends Conversation
{
    protected $phone;
    protected $question;
    protected $region;
    protected $manager;
    protected $user;
    protected $name;

    public function run()
    {
        $this->start();
    }

    public function start()
    {
        $this->user = BotUser::GetByMessengerId($this->getBot()->getUser()->getId());
        $this->region = Region::query()->where('id', '=', $this->user->region_id)->first();
        $this->manager = Manager::query()->where('id', '=', $this->region->manager_id)->first();


        if ($this->user->phone)
        {
            $this->checkName();
        }
        else
            {
                $this->AskPhone();
        }

    }

    public function AskPhone()
    {


            $this->ask('Нам необхідний Ваш номер телефону. Дякуємо!', function (Answer $answer, Conversation $conversation){

                if (isset($answer->getMessage()->getPayload()['contact']))
                {
                    $this->phone = $answer->getMessage()->getPayload()['contact']['phone_number'];

                    $this->user->phone = $this->phone;
                    $this->user->save();

                    $this->checkName();
                }
                elseif (preg_match("/^\+*3*8*0\d{3}\d{2}\d{2}\d{2}$/", $answer->getText()))
                {
                    $this->phone = $answer->getText();

                    $this->user->phone = $this->phone;
                    $this->user->save();

                    $this->checkName();
                }
                else
                {
                    $this->say('Будь ласка, скористайтеся кнопкою або введіть коректний номер телефону в форматі +380ХХххххххх');
                    $conversation->repeat();
                }
            }, TelegramKeyboardMaker::GetTelegramPhoneKeyboard());


    }

    public function checkName()
    {

        if ($this->user->name)
        {
            $this->AskQuestion();
        }
        else
        {
            $this->AskName();
        }

    }

    public function AskName()
    {

        $this->ask('Введіть Ваше ім\'я', function (Answer $answer, Conversation $conversation)
        {
            $this->name = $answer->getText();
            $this->user->name = $this->name;
            $this->user->save();

            $this->AskQuestion();
        });

    }

    public function AskQuestion()
    {

        $this->ask('Введіть Ваше запитання', function (Answer $answer, Conversation $conversation)
        {
            $this->question = $answer->getText();
            $this->finish();
        });

    }

    protected function finish()
    {

        $message = TelegramMessageMaker::SendMessageToManager($this->user->phone, $this->question, $this->user->name);

        if ($this->manager->msng_id > 0)
        {
            $this->bot->say($message, $this->manager->msng_id, TelegramDriver::class);
            //$this->bot->say($message, 787066206, TelegramDriver::class);

            $buttons = Buttons::query()->where('is_active', '=', true)->get();
            $this->say(
                TelegramMessageMaker::GetTelegramFinishQuestionMessage($this->user->phone, $this->question, $this->region, $this->manager),
                TelegramKeyboardMaker::GetTelegramPersistentKeyboard($buttons));
        }
        else
        {
            $buttons = Buttons::query()->where('is_active', '=', true)->get();
            $this->say(
                'Менеджер вашого регіону недоступний. Спробуйте пізніше',
                TelegramKeyboardMaker::GetTelegramPersistentKeyboard($buttons));
        }



    }
}
