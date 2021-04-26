<?php

namespace App\Orchid\Screens\Catalogue;

use App\Models\Brand;
use App\Models\Buttons;
use App\Models\MainMenu;
use Illuminate\Http\Request;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Toast;

class EditMenuScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Главное меню чатбота';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Включение\выключение элементов меню';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [

            'buttons' => Buttons::all()
            ,

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

            \Orchid\Support\Facades\Layout::table('buttons', [

                TD::set('name', __('Имя кнопки'))
                    ->cantHide()
                    ->filter(TD::FILTER_TEXT)
                ,

                TD::set('image', __('Эмоджи'))
                    ->cantHide()
                ,

                TD::set('is_active', __('Кнопка включена'))
                    ->cantHide()
                    ->render(function (Buttons $button){

                        if ($button->is_active)
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
                    ->render(function (Buttons $button) {
                        return DropDown::make()
                            ->icon('options-vertical')
                            ->list([

                                ModalToggle::make('Изменить')
                                    ->modal('oneAsyncModal')
                                    ->modalTitle($button->name)
                                    ->method('editButtons')
                                    ->asyncParameters([
                                        'buttons' => $button->id,
                                    ])
                                    ->icon('pencil'),

                            ]);
                    }),
            ]),

            Layout::modal('oneAsyncModal',
                [
                    Layout::rows([

                        Input::make('buttons.name')
                            ->type('text')
                            ->max(255)->readonly()
                            //->disabled()
                            ->title(__('Имя кнопки'))
                            ,


                        Input::make('buttons.image')
                            ->type('text')
                            ->title(__('Эмоджи'))
                            ->placeholder(__('Добавьте эмоджи')),

                        CheckBox::make('buttons.is_active')
                            ->title('Включение кнопки')
                            ->placeholder('Отметьте для включения кнопки меню')
                            ->sendTrueOrFalse(),


                    ]),

                ])->async('asyncGetButton')

        ];
    }

    public function asyncGetButton(Buttons $button): array
    {
        return [
            'buttons' => $button
        ];
    }

    public function editButtons(Buttons $button, Request $request)
    {
        $button->name = $request->input('buttons.name');
        $button->image = $request->input('buttons.image');
        $button->is_active = $request->input('buttons.is_active');

        $button->save();


        Toast::info(__('Кнопка сохранена.'));
    }
}
