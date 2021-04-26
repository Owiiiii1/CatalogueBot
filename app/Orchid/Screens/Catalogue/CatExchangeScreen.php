<?php

namespace App\Orchid\Screens\Catalogue;

use Orchid\Screen\Action;
use Orchid\Screen\Layout;
use Orchid\Screen\Screen;

class CatExchangeScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'CatExchangeScreen';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'CatExchangeScreen';

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
