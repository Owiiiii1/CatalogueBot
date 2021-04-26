<?php

namespace App\Orchid\Screens\Catalogue;

use App\Models\Category;
use App\Orchid\Layouts\Catalogue\CategoryEditLayout;
use App\Orchid\Layouts\Catalogue\CategoryListLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Layout;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class CatCategoriesScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Категории';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Тут можно добавить, удалить и изменить категрии товаров';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'categories' => Category::all()
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
        return [
            ModalToggle::make('Добавить категорию')
                ->modal('oneAsyncModal')
                ->modalTitle('Создание новой категории')
                ->method('saveCtg')
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
        return [  CategoryListLayout::class,

            \Orchid\Support\Facades\Layout::modal('oneAsyncModal', [
                CategoryEditLayout::class,
            ])->async('asyncGetCtg'),];
    }

    public function asyncGetCtg(Category $ctg): array
    {
        return [
            'categories'       => $ctg
        ];
    }

    public function saveCtg(Category $ctg, Request $request)
    {
        $ctg->CreateNew($request->input('categories.name'), $request->input('categories.description'), $request->input('categories.emoji'))->save();

        Toast::info(__('Категория сохранена.'));
    }

    public function editCtg(Category $ctg, Request $request)
    {
        $ctg->name = $request->input('categories.name');
        $ctg->description = $request->input('categories.description');
        $ctg->image = $request->input('categories.image');
        $ctg->save();

        Toast::info(__('Категория сохранена.'));
    }

    public function remove(Request $request)
    {
        Category::DeleteCat($request->get('id'));

        Toast::info(__('Категория удалена'));
    }
}
