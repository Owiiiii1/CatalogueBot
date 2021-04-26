<?php

namespace App\Orchid\Screens\Catalogue;

use App\Models\Brand;
use App\Models\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Upload;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Toast;

class CatCollectionsScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Коллекции ламината';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Тут можно добавить, удалить и изменить коллекции ламината';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'collections' => Collection::all()
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

            ModalToggle::make('Добавить коллекцию')
                ->modal('oneAsyncModal')
                ->modalTitle('Создание новой коллекции')
                ->method('saveCollection')
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

           Layout::table('collections', [

                TD::set('brand', __('Бренд'))
                    ->sort()
                    ->width('80px')
                    ->cantHide()
                    ->render(function (Collection $clct) {
                        return Str::limit($clct->brand->name, 200);
                    })

                ,

                TD::set('name', __('Название коллекции'))
                    ->width('150px')
                    ->sort()
                    ->cantHide()

                ,

                TD::set('description', __('Описание коллекции'))
                    ->width('100px')
                    ->cantHide()
                ,

                TD::set('image', __('Эмоджи'))
                    ->width('50px')
                    ->cantHide()
                ,

                TD::set('id', __('Имя файла'))
                    ->cantHide()
                    ->width('200px')
                    ->render(function (Collection $collection){
                        $fileURL = $collection->attachment()->first();
                        if (isset($fileURL))
                        {
                            return $fileURL->original_name;
                        }
                        else
                        {
                            return 'файл отсутствует';
                        }

                    })
                ,


                TD::set('settings', 'Настройки')
                    ->align(TD::ALIGN_CENTER)
                    ->width('100px')
                    ->render(function (Collection $collection) {
                        return DropDown::make()
                            ->icon('options-vertical')
                            ->list([

                                ModalToggle::make('Изменить')
                                    ->modal('oneAsyncModal')
                                    ->modalTitle($collection->name)
                                    ->method('editCollection')
                                    ->asyncParameters([
                                        'collections' => $collection->id,
                                    ])
                                    ->icon('pencil'),

                                Button::make(__('Удалить'))
                                    ->method('remove')
                                    ->confirm(__('Вы уверены, что хотите удалить коллекцию?'))
                                    ->parameters([
                                        'id' => $collection->id,
                                    ])
                                    ->icon('trash'),
                            ]);
                    }),
            ]),

            Layout::modal('oneAsyncModal',
                [
                    Layout::rows(
                        [


                            Select::make('collections.brand.id')
                                ->fromModel(Brand::class, 'name')
                                ->value('collection.brand.id')
                                ->required()
                                ->title('Бренд')
                                ->help('Выберите бренд'),


                        Input::make('collections.name')
                            ->type('text')
                            ->max(255)
                            ->required()
                            ->title(__('Имя коллекции'))
                            ->placeholder(__('Введите наименование коллекции')),

                        Input::make('collections.description')
                            ->type('text')
                            ->required()
                            ->title(__('Описание коллекции'))
                            ->placeholder(__('Укажите описание коллекции')),

                        Input::make('collections.image')
                            ->type('text')
                            ->title(__('Эмоджи'))
                            ->placeholder(__('Добавьте эмоджи')),

                            Upload::make('collections.attachment')
                                ->title(__('Файл коллекции'))
                                ->placeholder(__('Добавьте файл'))


                        ]),

                ])->async('asyncGetCollection')
        ];
    }

    public function asyncGetCollection(Collection $collection): array
    {
        return [
            'collections' => $collection
        ];
    }

    public function saveCollection(Request $request)
    {

        $collect = Collection::CreateNew($request->input('collections.name'), $request->input('collections.description'), $request->input('collections.image'), $request->input('collection.brand.id'));

        $collect->attachment()->syncWithoutDetaching($request->input('collections.attachment', [])
        );

        Toast::info(__('Коллекция сохранена.'));
    }

    public function editCollection(Collection $col, Request $request)
    {
        $col->name = $request->input('collections.name');
        $col->description = $request->input('collections.description');
        $col->image = $request->input('collections.image');
        $col->brand_id = $request->input('collections.brand.id');

        if ($request->input('collections.attachment'))
        {
            $col->attachment()->each(function ($item){$item->delete();});
            $col->attachment()->syncWithoutDetaching($request->input('collections.attachment', []));

        }

        $col->save();

        Toast::info(__('Коллекция сохранена.'));
    }

    public function remove(Request $request)
    {
        $col = Collection::query()->findOrFail($request->get('id'));

        $col->attachment()->each(function ($item){$item->delete();});

        $col->delete();

        Toast::info(__('Коллекция удалена'));
    }
}
