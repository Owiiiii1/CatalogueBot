<?php

namespace App\Orchid\Screens\Catalogue;

use App\Models\Category;
use App\Models\Price;
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

class CatPricesScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Прайсы';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Добавление\изменение\удаление прайсов';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [

            'prices' => Price::all()

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
            ModalToggle::make('Добавить прайс')
                ->modal('oneAsyncModal')
                ->modalTitle('Добавление нового прайса')
                ->method('savePrice')
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

            \Orchid\Support\Facades\Layout::table('prices', [

                TD::set('cat_id', __('Категория'))
                    ->sort()
                    ->width('150px')
                    ->cantHide()
                    ->render(function (Price $price) {

                        //return Str::limit($price->category->name, 200);

                        $cat = Category::query()->where('id', '=', $price->cat_id)->first();
                        return $cat->name;
                    })
                ,


                TD::set('name', __('Название прайса'))
                    ->sort()
                    ->width('200px')
                    ->cantHide()
                ,

                TD::set('id', __('Имя файла'))
                    ->cantHide()
                    ->width('150px')
                    ->render(function (Price $prices){
                        $fileURL = $prices->attachment()->first();
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
                    ->render(function (Price $prices) {
                        return DropDown::make()
                            ->icon('options-vertical')
                            ->list([

                                ModalToggle::make('Изменить')
                                    ->modal('oneAsyncModal')
                                    ->modalTitle($prices->name)
                                    ->method('editPrice')
                                    ->asyncParameters([
                                        'prices' => $prices->id,
                                    ])
                                    ->icon('pencil'),

                                Button::make(__('Удалить'))
                                    ->method('remove')
                                    ->confirm(__('Вы уверены, что хотите удалить прайс?'))
                                    ->parameters([
                                        'id' => $prices->id,
                                    ])
                                    ->icon('trash'),
                            ]);
                    }),
            ]),

            Layout::modal('oneAsyncModal',
                [
                    Layout::rows(
                        [

                            Select::make('prices.category.id')
                                ->fromModel(Category::class, 'name')
                                ->value('prices.category.id')
                                ->required()
                                ->title('Категория')
                                ->help('Выберите категорию'),

                            Input::make('prices.name')
                                ->type('text')
                                ->max(255)
                                ->required()
                                ->title(__('Название прайса'))
                                ->placeholder(__('Введите название прайса')),

                            Upload::make('prices.attachment')
                                ->title(__('Файл прайса'))
                                ->placeholder(__('Добавьте файл'))


                        ]),

                ])->async('asyncGetPrice')
        ];
    }

    public function asyncGetPrice(Price $prices): array
    {
        return [
            'prices' => $prices
        ];
    }

    /**
     * @param Request $request
     */
    public function savePrice(Request $request)
    {

        $prices = Price::CreateNew($request->input('prices.name'), $request->input('prices.category.id'));

        $prices->attachment()->syncWithoutDetaching($request->input('prices.attachment'));

        Toast::info(__('Прайс сохранен.'));
    }

    /**
     * @param Price $prices
     * @param Request $request
     */
    public function editPrice(Price $prices, Request $request)
    {
        $prices->name = $request->input('prices.name');
        $prices->cat_id = $request->input('prices.category.id');
        $prices->attachment()->syncWithoutDetaching($request->input('prices.attachment', []));

        $prices->save();

        Toast::info(__('Прайс сохранен.'));
    }

    public function remove(Request $request)
    {
        $prices = Price::query()->findOrFail($request->get('id'));

        $prices->attachment()->each(function ($item){$item->delete();});

        $prices->delete();

        Toast::info(__('Прайс удален'));
    }
}
