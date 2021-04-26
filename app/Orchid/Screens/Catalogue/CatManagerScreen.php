<?php

namespace App\Orchid\Screens\Catalogue;

use App\Models\Manager;
use Illuminate\Http\Request;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class CatManagerScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Менеджеры регионов';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Создание\удаление\измнение менеджеров регионов';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'manager' => Manager::all()
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
            ModalToggle::make('Добавить менеджера')
                ->modal('oneAsyncModal')
                ->modalTitle('Создание нового менеджера')
                ->method('saveManager')
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
            Layout::table('manager', [

                TD::set('name', __('Имя менеджера'))
                    ->width('250px')
                    ->sort()
                    ->cantHide()
                    ->filter(TD::FILTER_TEXT)
                ,

                TD::set('phone', __('Телефон'))
                    ->width('280px')
                    ->cantHide()
                ,

                TD::set('msng_id', __('Телеграмм'))
                    ->width('280px')
                    ->cantHide()
                ,

                TD::set('settings', 'Настройки')
                    ->align(TD::ALIGN_CENTER)
                    ->width('100px')
                    ->render(function (Manager $manager) {
                        return DropDown::make()
                            ->icon('options-vertical')
                            ->list([

                                ModalToggle::make('Изменить')
                                    ->modal('oneAsyncModal')
                                    ->modalTitle($manager->name)
                                    ->method('editManager')
                                    ->asyncParameters([
                                        'manager' => $manager->id,
                                    ])
                                    ->icon('pencil'),

                                Button::make(__('Удалить'))
                                    ->method('remove')
                                    ->confirm(__('Вы уверены, что хотите удалить менеджера?'))
                                    ->parameters([
                                        'id' => $manager->id,
                                    ])
                                    ->icon('trash'),
                            ]);
                    }),
            ]),

            Layout::modal('oneAsyncModal',
                [
                    Layout::rows([

                        Input::make('manager.name')
                            ->type('text')
                            ->max(255)
                            ->required()
                            ->title(__('Имя менеджера'))
                            ->placeholder(__('Введите имя менеджера')),

                        Input::make('manager.phone')
                            ->type('text')
                            ->title(__('Телефон'))
                            ->placeholder(__('Укажите телефон менеджера')),

                        Input::make('manager.msng_id')
                            ->type('text')
                            ->title(__('Telegram'))
                            ->placeholder(__('Укажите id Telegram')),

                    ]),

                ])->async('asyncGetManager')
        ];
    }

    public function asyncGetManager(Manager $manager): array
    {
        return [
            'manager' => $manager
        ];
    }

    public function saveManager(Request $request)
    {
        Manager::CreateNew($request->input('manager.name'), $request->input('manager.phone'), $request->input('manager.msng_id'));

        Toast::info(__('Менеджер добавлен.'));
    }

    public function editManager(Manager $manager, Request $request)
    {
        $manager->name = $request->input('manager.name');
        $manager->phone = $request->input('manager.phone');
        $manager->msng_id = $request->input('manager.msng_id');
        $manager->save();

        Toast::info(__('Изменения сохранены.'));
    }

    public function remove(Request $request)
    {
        Manager::DeleteManager($request->get('id'));

        Toast::info(__('Менеджер удален'));
    }
}
