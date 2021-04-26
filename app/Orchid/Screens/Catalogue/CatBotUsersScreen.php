<?php

namespace App\Orchid\Screens\Catalogue;

use App\Models\BotUser;
use App\Orchid\Layouts\Catalogue\BotUserListLayout;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\Drivers\Telegram\TelegramDriver;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layout;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class CatBotUsersScreen extends Screen
{
    /**
     * Свойства имя и описание отвечают за то,
     * какое название будет отображаться
     * на экране пользователя и в заголовках.
     */
    public $name = 'Клиенты';
    public $description = 'Изменение\удаление\отправка сообщений пользователям чатбота';

    /**
     * Метод определяющий все входные данные экрана,
     * именно в нём должны вызываться запросы к базе данных,
     * api или любые другие (не обязательно явно),
     * результатом которого должен быть массив,
     * обращение к которым будут использоваться его ключи.
     */
    public function query(): array
    {
        return [
            'botusers' => BotUser::all(),
        ];
    }

    /**
     * Определяет управляющие кнопки и события,
     * которые должны будут произойти по нажатию
     */
    public function commandBar(): array
    {
        return [];
    }

    /**
     * Набор отображений,
     * строк, таблиц, графиков,
     * модальных окон и их комбинации.
     */
    public function layout(): array
    {
        return [
            BotUserListLayout::class,

            \Orchid\Support\Facades\Layout::modal('SendSingleMessageModal', [

                \Orchid\Support\Facades\Layout::rows([

                    TextArea::make('message')->title('Сообщение пользователю')

                ]),

            ])->applyButton('Отправить ')->title('Отправить сообщение пользователю'),

        ];
    }

    public function SendSingleMessage(Request $request)
    {

        $config = [ 'telegram' => [ 'token' => env('TELEGRAM_TOKEN') ] ];

        DriverManager::loadDriver(TelegramDriver::class);

        $botman = BotManFactory::create($config);


        $botman->say(
            $request->input('message'),
            $request->input('msng_id'),
            TelegramDriver::class
        );


        Toast::info('Сообщение успешно отправлено');

        return back();
    }


}
