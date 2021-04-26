<?php

namespace App\Orchid\Screens\Catalogue;

use Orchid\Screen\Screen;

class CatItemsScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'CatItemsScreen';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'CatItemsScreen';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [];
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
        return [];
    }
}
