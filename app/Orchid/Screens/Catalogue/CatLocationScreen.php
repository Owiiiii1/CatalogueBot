<?php


namespace App\Orchid\Screens\Catalogue;

use App\Models\Brand;
use App\Models\Manager;
use App\Models\Region;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class CatLocationScreen extends Screen
{

    /**
     * @var string
     */
    public $name = 'Местоположения';

    /**
     * @var string
     */
    public $description = 'Редактирование и привязки местоположений каталога';


    /**
     * @return array
     */
    public function query(): array
    {
        return [
                'locations' => Region::all()
        ];
    }


    /**
     * @return array
     */
    public function commandBar(): array
    {
        return [
            ModalToggle::make('Добавить локацию')
                ->modal('oneAsyncModal')
                ->modalTitle('Создание новой локации')
                ->method('saveRegion')
                ->icon('plus'),
        ];
    }


    /**
     * @return array
     */
    public function layout(): array
    {
        return [
            Layout::table('locations', [

                TD::set('name', __('Название локации'))
                    ->width('250px')
                    ->sort()
                    ->cantHide()
                    ->filter(TD::FILTER_TEXT)
                ,

                TD::set('description', __('Описание'))
                    ->width('280px')
                    ->sort()
                    ->cantHide()
                ,

                TD::set('manager_id', __('Менеджер'))
                    ->sort()
                    ->width('280px')
                    ->cantHide()
                    ->render(function (Region $region) {
                        $name = Manager::query()->where('id', '=', $region->manager_id)->pluck('name')->first();
                        return $name ?? '-';
                    })
                ,

                TD::set('settings', 'Настройки')
                    ->align(TD::ALIGN_CENTER)
                    ->width('100px')
                    ->render(function (Region $region) {
                        return DropDown::make()
                            ->icon('options-vertical')
                            ->list([

                                ModalToggle::make('Изменить')
                                    ->modal('oneAsyncModal')
                                    ->modalTitle($region->name)
                                    ->method('editRegion')
                                    ->asyncParameters([
                                        'brands' => $region->id,
                                    ])
                                    ->icon('pencil'),

                                Button::make(__('Удалить'))
                                    ->method('remove')
                                    ->confirm(__('Вы уверены, что хотите удалить бренд?'))
                                    ->parameters([
                                        'id' => $region->id,
                                    ])
                                    ->icon('trash'),
                            ]);
                    }),
            ]),

            Layout::modal('oneAsyncModal',
                [
                    Layout::rows([

                        Input::make('locations.name')
                            ->type('text')
                            ->max(255)
                            ->required()
                            ->title(__('Имя локации'))
                            ->placeholder(__('Введите наименование локации')),

                        Input::make('locations.description')
                            ->type('text')
                            ->title(__('Описание локации'))
                            ->placeholder(__('Укажите описание локации')),

                        Select::make('locations.manager_id')
                            ->fromModel(Manager::class, 'name')
                            ->value('locations.manager_id')
                            ->title('Менеджер')
                            ->help('Выберите менеджера'),

                    ]),

                ])->async('asyncGetBrand')
        ];
    }

    public function asyncGetBrand(Region $region): array
    {
        return [
            'locations' => $region
        ];
    }

    public function saveRegion(Request $request)
    {
        Region::CreateNew($request->input('locations.name'), $request->input('locations.description'), $request->input('locations.manager_id'));

        Toast::info(__('Регион сохранен.'));
    }

    public function editRegion(Region $region, Request $request)
    {
        $region->name = $request->input('locations.name');
        $region->description = $request->input('locations.description');
        $region->manager_id = $request->input('locations.manager_id');
        $region->save();

        Toast::info(__('Регион сохранен.'));
    }

    public function remove(Request $request)
    {
        Region::DeleteRegion($request->get('id'));

        Toast::info(__('Регион удален'));
    }
}
