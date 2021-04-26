<?php


namespace App\Conversations;


use App\KeyboardMakers\TelegramKeyboardMaker;
use App\MessageMakers\TelegramMessageMaker;
use App\Models\BotUser;
use App\Models\Buttons;
use App\Models\Region;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;

class SelectRegionConversation extends Conversation
{

    public function run()
    {
        $this->askRegion();
    }

    public function askRegion()
    {

        $regions = Region::all();


        //$keyboard = TelegramKeyboardMaker::GetSelectRegionsKeyboard($regions);

        $this->ask('Ваш регіон не обраний. Будь ласка, виберіть регіон зі списку:', function (Answer $answer, Conversation $conversation){

            if ($answer->isInteractiveMessageReply())
            {
                $user = BotUser::query()->where('msng_id', '=', $this->getBot()->getUser()->getId())->first();
                $user->region_id = $answer->getValue();
                $user->save();

                $buttons = Buttons::query()->where('is_active', '=', true)->get();

                $this->say(
                        TelegramMessageMaker::GetGreetingsMessage(),
                        TelegramKeyboardMaker::GetTelegramPersistentKeyboard($buttons)
                    );
            }
            else
            {
                $this->say('Будь ласка, скористайтеся кнопкою');
                $conversation->repeat();
            }
        }, TelegramKeyboardMaker::GetSelectRegionsKeyboard($regions));


    }

}
