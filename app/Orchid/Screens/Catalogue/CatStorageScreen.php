<?php

namespace App\Orchid\Screens\Catalogue;

use App\Models\Buttons;
use App\Models\Region;
use App\Models\Storage;
use Illuminate\Http\Request;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class CatStorageScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Настройка складов';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'включение\выключение';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [

            'storage' => Storage::all()

        ];
    }

    /**
     * Button commands.
     *
     * @return Action[]
     */
    public function commandBar(): array
    {
        return [
            ModalToggle::make('Добавить склад')
                ->modal('oneAsyncModal')
                ->modalTitle('Создание нового склада')
                ->method('saveStorage')
                ->icon('plus'),
        ];
    }

    /**
     * Views.
     *
     * @return Layout[]
     */
    public function layout(): array
    {
        return [
            Layout::table('storage', [

                TD::set('name', __('Имя склада'))
                    ->cantHide()
                    ->filter(TD::FILTER_TEXT)
                ,


                TD::set('is_active', __('Склад участвует в поиске'))
                    ->cantHide()
                    ->render(function (Storage $storage){

                        if ($storage->is_active)
                        {
                            return "✅ Включено";
                        }
                        else
                        {
                            return "❌ Выключено";
                        }
                    }),

                TD::set('settings', 'Настройки')
                    ->align(TD::ALIGN_CENTER)
                    ->width('100px')
                    ->render(function (Storage $storage) {
                        return DropDown::make()
                            ->icon('options-vertical')
                            ->list([

                                ModalToggle::make('Изменить')
                                    ->modal('oneAsyncModal')
                                    ->modalTitle($storage->name)
                                    ->method('editStorage')
                                    ->asyncParameters([
                                        'storage' => $storage->id,
                                    ])
                                    ->icon('pencil'),

                                Button::make(__('Удалить'))
                                    ->method('remove')
                                    ->confirm(__('Вы уверены, что хотите удалить склад?'))
                                    ->parameters([
                                        'id' => $storage->id,
                                    ])
                                    ->icon('trash'),

                            ]);
                    }),
            ]),

            Layout::modal('oneAsyncModal',
                [
                    Layout::rows([

                        Input::make('storage.name')
                            ->type('text')
                            ->required()
                            //->disabled()
                            ->title(__('Имя склада'))
                        ,

                        CheckBox::make('storage.is_active')
                            ->title('Включение склада')
                            ->placeholder('Отметьте для включения склада в поиск')
                            ->sendTrueOrFalse(),


                    ]),

                ])->async('asyncGetStorage')
        ];
    }

    public function asyncGetStorage(Storage $storage): array
    {
        return [
            'storage' => $storage
        ];
    }

    public function saveStorage(Request $request)
    {
        Storage::CreateNew($request->input('storage.name'), $request->input('storage.is_active'));

        Toast::info(__('Склад сохранен.'));
    }

    public function editStorage(Storage $storage, Request $request)
    {
        $storage->name = $request->input('storage.name');
        $storage->is_active = $request->input('storage.is_active');

        $storage->save();


        Toast::info(__('Склад сохранен.'));
    }

    public function remove(Request $request)
    {
        Storage::DeleteStorage($request->get('id'));

        Toast::info(__('Склад удален'));
    }

}
