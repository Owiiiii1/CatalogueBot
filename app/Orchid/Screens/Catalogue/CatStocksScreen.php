<?php

namespace App\Orchid\Screens\Catalogue;

use App\Models\Category;
use App\Models\Stocks;
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

class CatStocksScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Акции';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Тут можно добавить, удалить и изменить акции по категориям';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'stocks' => Stocks::all()
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
            ModalToggle::make('Добавить акцию')
                ->modal('oneAsyncModal')
                ->modalTitle('Создание новой акции')
                ->method('saveStocks')
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

            Layout::table('stocks', [

                TD::set('cat_id', __('Категория'))
                    ->sort()
                    ->cantHide()
                    ->render(function (Stocks $stocks) {

                        $cat = Category::query()->where('id', '=', $stocks->cat_id)->first();
                        return $cat->name;
                    })
                ,

                TD::set('name', __('Название акции'))
                    ->sort()
                    ->cantHide()
                ,

                TD::set('id', __('Имя файла'))
                    ->cantHide()
                    ->render(function (Stocks $stocks){
                        $fileURL = $stocks->attachment()->first();
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

                TD::set('created_at', __('Дата создания'))
                    ->sort()
                    ->render(function (Stocks $stocks) {
                        return $stocks->created_at->toDateTimeString();
                    }),

                TD::set('settings', 'Настройки')
                    ->align(TD::ALIGN_CENTER)
                    ->width('100px')
                    ->render(function (Stocks $stocks) {
                        return DropDown::make()
                            ->icon('options-vertical')
                            ->list([

                                ModalToggle::make('Изменить')
                                    ->modal('oneAsyncModal')
                                    ->modalTitle($stocks->name)
                                    ->method('editStocks')
                                    ->asyncParameters([
                                        'stocks' => $stocks->id,
                                    ])
                                    ->icon('pencil'),

                                Button::make(__('Удалить'))
                                    ->method('remove')
                                    ->confirm(__('Вы уверены, что хотите удалить акцию?'))
                                    ->parameters([
                                        'id' => $stocks->id,
                                    ])
                                    ->icon('trash'),
                            ]);
                    }),
            ]),

            Layout::modal('oneAsyncModal',
                [
                    Layout::rows(
                        [

                            Select::make('stocks.category.id')
                                ->fromModel(Category::class, 'name')
                                ->value('stocks.category.id')
                                ->required()
                                ->title('Категория')
                                ->help('Выберите категорию'),

                            Input::make('stocks.name')
                                ->type('text')
                                ->max(255)
                                ->required()
                                ->title(__('Наименование акции'))
                                ->placeholder(__('Введите наименование акции')),

                            Upload::make('stocks.attachment')
                                ->title(__('Файл акции'))
                                ->acceptedFiles('image/*,application/pdf,.psd')
                                ->placeholder(__('Добавьте файл'))

                        ]),
                ])->async('asyncGetStocks')
        ];
    }

    public function asyncGetStocks(Stocks $stocks): array
    {
        return [
            'stocks' => $stocks
        ];
    }

    public function saveStocks(Request $request)
    {

        $stock = Stocks::CreateNew($request->input('stocks.name'), $request->input('stocks.category.id'));

        $stock->attachment()->syncWithoutDetaching($request->input('stocks.attachment', [])
        );

        Toast::info(__('Акция сохранена.'));
    }

    public function editStocks(Stocks $stocks, Request $request)
    {
        $stocks->name = $request->input('stocks.name');
        $stocks->cat_id = $request->input('stocks.category.id');

        if ($request->input('stocks.attachment'))
        {
            $stocks->attachment()->each(function ($item){$item->delete();});
            $stocks->attachment()->syncWithoutDetaching($request->input('stocks.attachment', []));
        }

        $stocks->save();

        Toast::info(__('Акция сохранена.'));
    }

    public function remove(Request $request)
    {
        $stocks = Stocks::query()->findOrFail($request->get('id'));

        $stocks->attachment()->each(function ($item){$item->delete();});

        $stocks->delete();

        Toast::info(__('Акция удалена'));
    }

}
