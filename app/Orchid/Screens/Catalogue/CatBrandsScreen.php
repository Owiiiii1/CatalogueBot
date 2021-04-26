<?php

namespace App\Orchid\Screens\Catalogue;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;
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

class CatBrandsScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Бренды';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Тут можно добавить, удалить и изменить брендов';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'brands' => Brand::all()
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
            ModalToggle::make('Добавить бренд')
                ->modal('oneAsyncModal')
                ->modalTitle('Создание нового бренда')
                ->method('saveBrand')
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

            Layout::table('brands', [

        TD::set('name', __('Название бренда'))
            ->sort()
            ->width('250px')
            ->cantHide()
            ->filter(TD::FILTER_TEXT),

        TD::set('description', __('Описание'))
            ->width('300px')
            ->cantHide(),

                TD::set('id', __('Имя файла'))
                    ->cantHide()
                    ->render(function (Brand $brand){
                        $fileURL = $brand->attachment()->first();
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

        TD::set('image', __('Эмоджи'))
            ->width('50px')
            ->cantHide(),

        TD::set('settings', 'Настройки')
            ->align(TD::ALIGN_CENTER)
            ->width('100px')
            ->render(function (Brand $brand) {
                return DropDown::make()
                    ->icon('options-vertical')
                    ->list([

                        ModalToggle::make('Изменить')
                            ->modal('oneAsyncModal')
                            ->modalTitle($brand->name)
                            ->method('editBrand')
                            ->asyncParameters([
                                'brands' => $brand->id,
                            ])
                            ->icon('pencil'),

                        Button::make(__('Удалить'))
                            ->method('remove')
                            ->confirm(__('Вы уверены, что хотите удалить бренд?'))
                            ->parameters([
                                'id' => $brand->id,
                            ])
                            ->icon('trash'),
                    ]);
            }),
    ]),

            Layout::modal('oneAsyncModal',
                [
                    Layout::rows([

                        Input::make('brands.name')
                            ->type('text')
                            ->max(255)
                            ->required()
                            ->title(__('Имя бренда'))
                            ->placeholder(__('Введите наименование бренда')),

                        Input::make('brands.description')
                            ->type('text')
                            ->required()
                            ->title(__('Описание бренда'))
                            ->placeholder(__('Укажите описание бренда')),

                        Input::make('brands.image')
                            ->type('text')
                            ->title(__('Эмоджи'))
                            ->placeholder(__('Добавьте эмоджи')),



                        Select::make('brands.categories.')
                            ->fromModel(Category::class, 'name')
                            ->multiple()
                            ->title('Категории')
                            ->help('Выберите категории, соответствующие данному бренду'),

                        Upload::make('brands.attachment')
                            ->title(__('Файл каталога'))
                            ->acceptedFiles('image/*,application/pdf,.psd')
                            ->placeholder(__('Добавьте файл'))

                    ]),

                ])->async('asyncGetBrand')
        ];
    }

    public function asyncGetBrand(Brand $brand): array
    {
        return [
            'brands' => $brand
        ];
    }

    public function saveBrand(Request $request)
    {
        $brand = Brand::CreateNew($request->input('brands.name'), $request->input('brands.description'), $request->input('brands.image'));

        $brand->categories()->sync($request->input('brands.categories'));

        $brand->attachment()->syncWithoutDetaching($request->input('brands.attachment', [])
        );

        Toast::info(__('Бренд сохранен.'));
    }

    public function editBrand(Brand $brand, Request $request)
    {
        $brand->name = $request->input('brands.name');
        $brand->description = $request->input('brands.description');
        $brand->image = $request->input('brands.image');
        $brand->save();

        $brand->categories()->sync($request->input('brands.categories'));

        if ($request->input('brands.attachment'))
        {
            $brand->attachment()->syncWithoutDetaching($request->input('brands.attachment', []));
        }

        Toast::info(__('Бренд сохранен.'));
    }

    public function remove(Request $request)
    {
        Brand::DeleteBrand($request->get('id'));

        Toast::info(__('Бренд удален'));
    }



}
