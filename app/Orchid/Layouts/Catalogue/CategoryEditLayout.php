<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Catalogue;

use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;

class CategoryEditLayout extends Rows
{

    /**
     * @return array
     */
    public function fields(): array
    {
        return [
            Input::make('categories.name')
                ->type('text')
                ->max(255)
                ->required()
                ->title(__('Имя категории'))
                ->placeholder(__('Введите имя категории')),

            Input::make('categories.description')
                ->type('text')
                ->title(__('Описание категории'))
                ->placeholder(__('Укажите описание категории')),

            Input::make('categories.image')
                ->type('text')
                ->title(__('Эмоджи'))
                ->placeholder(__('Добавьте эмоджи')),

        ];
    }
}
