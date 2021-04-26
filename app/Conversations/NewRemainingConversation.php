<?php


namespace App\Conversations;


use App\KeyboardMakers\TelegramKeyboardMaker;
use App\MessageMakers\TelegramMessageMaker;
use App\Models\Storage;
use BotMan\BotMan\Messages\Attachments\Image;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class NewRemainingConversation extends Conversation
{

    protected $number;
    protected $message = '0';
    protected $attachment;
    protected $i = 0;
    protected $x = 0;


    public function run()
    {
        $this->start();
    }

    public function start()
    {
        $this->ask('Введіть артикул \ номер товару', function (Answer $answer, Conversation $conversation)
        {
            $this->number = $answer->getText();

            if (iconv_strlen($this->number) > 3)
            {
                $this->find();
            }
            else
            {
                $this->getBot()->reply('Мінімальна кількість символів для пошуку - 4. Спробуйте знову');
                $this->start();
            }


        });
    }

    public function find()
    {
        $files = \Illuminate\Support\Facades\File::files(storage_path().'/app/stocks');

        if ($files)
        {
            foreach ($files as $file)
            {
                $fileName = $file->getFilename();

                $data = json_decode(file_get_contents(storage_path() . '/app/stocks/'.$fileName), true);

                foreach ($data as $datum)
                {
                    foreach ($datum as $item)
                    {
                        if(stristr($item["sku"], $this->number))
                        {
                            $this->i = 0;

                            $this->message = 'Знайдений залишок:'. "\n\n".'Артикул:'.$item["sku"]."\n". $item['name']. "\n\n";

                            $file = \Illuminate\Support\Facades\File::isFile(public_path().'/storage/Images/'.$item["sku"].'.jpg');

                            if ($file)
                            {
                                $this->attachment = new Image('https://witeksbot.botgrid.net/storage/Images/'. $item["sku"].'.jpg');
                            }
                            else
                            {
                                $this->attachment = new Image('https://witeksbot.botgrid.net/storage/Images/witexlogo.png');
                            }

                            Log::info(print_r($this->attachment, true));

                            $appendData = '';

                            foreach ($item["stocks"] as $stocks)
                            {
                                $storages = Storage::query()->where('is_active', '=', 1)->pluck('name')->toArray();



                                if (in_array($stocks['warehouse'], $storages))
                                {
                                    if($stocks['warehouse'] == "Основной склад (Киев)")
                                    {
                                         $appendData = 'Склад: "' . $stocks['warehouse']. '" : ' . $stocks['freeamount']. ' '.$item['unit']."\n" . $appendData ;

                                        if($stocks["expected"]["delivery_date"] != "" && (int) $stocks["expected"]["amount"] > 0 )
                                        {
                                            $appendData .= "Очікується " . $stocks["expected"]["amount"] ." ".$item['unit'] ." на ".Carbon::parse($stocks["expected"]["delivery_date"])->toDateString();
                                        }

                                    }
                                    else
                                    {
                                        $appendData .= 'Склад: "' . $stocks['warehouse']. '" : ' . $stocks['freeamount']. ' '.$item['unit']."\n";

                                        if($stocks["expected"]["delivery_date"] != "" && (int) $stocks["expected"]["amount"] > 0 )
                                        {
                                            $appendData .= "Очікується " . $stocks["expected"]["amount"] ." ".$item['unit'] ." на ".Carbon::parse($stocks["expected"]["delivery_date"])->toDateString();
                                        }

                                    }

                                    $this->i++;
                                    $this->x++;
                                }
                            }

                            $this->message .= $appendData;

                            $this->message .= "\n\n";

                            $message = OutgoingMessage::create($this->message)->withAttachment($this->attachment);

                            if ($this->i > 0)
                            {
                                $this->getBot()->reply($message, TelegramKeyboardMaker::SendOrderKeyboard($item["sku"]));

                            }
                        }
                    }
                }
                if ($this->message == '0' || $this->x == 0)
            {
                $this->getBot()->reply('Товар відсутній або помилка у артиклi. Спробуйте знову');
                $this->start();
            }
            }}

        else
        {
            $this->getBot()->reply('Ошибка файла остатков. Попробуйте позже или свяжитесь с администрацией');
        }
    }

}
