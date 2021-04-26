<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Catalogue;


use App\Models\Category;
use Orchid\Platform\Models\User;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class CategoryListLayout extends Table
{
    /**
     * @var string
     */
    public $target = 'categories';

    /**
     * @return array
     */
    public function columns(): array
    {
        return [
            TD::set('name', __('Название категории'))
                ->sort()
                ->width('250px')
                ->cantHide()
                ->filter(TD::FILTER_TEXT)
            ,

            TD::set('description', __('Описание'))
                ->width('300px')
                ->cantHide()
            ,

            TD::set('image', __('Эмоджи'))
                ->width('50')
                ->cantHide()
            ,

            TD::set('settings', 'Настройки')
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function (Category $ctg) {
                    return DropDown::make()
                        ->icon('options-vertical')
                        ->list([

                            ModalToggle::make('Изменить')
                                ->modal('oneAsyncModal')
                                ->modalTitle($ctg->name)
                                ->method('editCtg')
                                ->asyncParameters([
                                    'categories' => $ctg->id,
                                ])
                                ->icon('pencil'),

                            Button::make(__('Удалить'))
                                ->method('remove')
                                ->confirm(__('Вы уверены, что хотите удалить категорию?'))
                                ->parameters([
                                    'id' => $ctg->id,
                                ])
                                ->icon('trash'),
                        ]);
                }),


        ];
    }
}
