<?php

namespace App\Orchid\Screens\Catalogue;

use App\Models\Region;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Label;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Upload;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Toast;

class CatSettingsScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Настройки чат-бота';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Раздел в разработке';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [

            'exchangeUSD' => Settings::query()->where('name', '=', 'Exchange_usd')->first(),
            'exchangeEU' => Settings::query()->where('name', '=', 'Exchange_eu')->first(),
            'exchangeEUNBU' => Settings::query()->where('name', '=', 'Exchange_euNBU')->first(),
            'www' => Settings::query()->where('name', '=', 'www')->first(),
            'contacts' => Settings::query()->where('name', '=', 'contacts')->first(),

        ];
    }

    /**
     * Button commands.
     *
     * @return Action[]
     */
    public function commandBar(): array
    {
        return [];
    }

    /**
     * Views.
     *
     * @return Layout[]
     */
    public function layout(): array
    {
        return [

            Layout::rows([

                Input::make('exchangeUSD.unit1')
                    ->title('Доллар США наличный')
                    ->placeholder('Введите значение здесь')
                    ->required(),

                Input::make('exchangeEU.unit1')
                    ->title('Евро наличный')
                    ->placeholder('Введите значение здесь')
                    ->required(),

                Input::make('exchangeEUNBU.unit1')
                    ->title('Евро НБУ')
                    ->placeholder('Введите значение здесь')
                    ->required(),

                Input::make('www.unit1')
                    ->title('Адрес сайта')
                    ->placeholder('Введите значение здесь')
                    ->required(),

                TextArea::make('contacts.unit1')
                    ->rows(8),

        ]),
            Layout::rows([

            Button::make('Сохранить')
                ->method('SaveSettings')
                ->type(Color::DEFAULT())
                ->block(),
            ]),

        ];
    }

    public function SaveSettings(Settings $settings, Request $request)
    {

            $settings->set('www', $request->input('www.unit1'));
        $settings->set('Exchange_usd', $request->input('exchangeUSD.unit1'));
        $settings->set('Exchange_eu', $request->input('exchangeEU.unit1'));
        $settings->set('Exchange_euNBU', $request->input('exchangeEUNBU.unit1'));
        $settings->set('contacts', $request->input('contacts.unit1'));

        //Log::info($request->input('upload'));

//        $settings->attachment()->syncWithoutDetaching($request->input('upload')
//        );

        Toast::info(__('Настройки изменены.'));
    }
}
