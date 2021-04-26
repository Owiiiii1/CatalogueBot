<?php


namespace App\Conversations;


use App\KeyboardMakers\TelegramKeyboardMaker;
use App\Models\BotUser;
use App\Models\Buttons;
use App\Models\Manager;
use App\Models\Region;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\Drivers\Telegram\TelegramDriver;

class SendOrderConversation extends Conversation
{

    protected $item;
    protected $quantity;
    protected $region;
    protected $manager;
    protected $user;
    protected $name;

    function __construct($item)
    {
        $this->item = $item;
    }

    public function run()
    {
        $this->user = BotUser::GetByMessengerId($this->getBot()->getUser()->getId());
        $this->region = Region::query()->where('id', '=', $this->user->region_id)->first();
        $this->manager = Manager::query()->where('id', '=', $this->region->manager_id)->first();

        $this->askQuantity();
    }

    public function askQuantity()
    {

        $this->ask('Введіть необхідну кількість:', function (Answer $answer, Conversation $conversation)
        {
            $this->quantity = $answer->getText();

            if ($this->user->phone)
            {
                $this->checkName();
            }
            else
            {
                $this->AskPhone();
            }

        });

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
            $this->finish();
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

            $this->finish();
        });

    }

    protected function finish()
    {


        $message = 'Запрошен товар:'."\n\n".
            'Артикул:  '. $this->item."\n\n".
            'Требуемое кол-во:  '. $this->quantity."\n\n".
            'Телефон клиента: '.$this->user->phone.
            'Имя клиента:  '. $this->user->name;

        $this->bot->say($message, $this->manager->msng_id, TelegramDriver::class);

        $buttons = Buttons::query()->where('is_active', '=', true)->get();

        $this->say(
            'Запит відправлено', TelegramKeyboardMaker::GetTelegramPersistentKeyboard($buttons)
            );
    }

}
