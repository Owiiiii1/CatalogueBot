<?php

namespace App\Orchid\Screens\Catalogue;

use Orchid\Screen\Action;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Screen;

class CatStatsScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Статистика';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Раздел в разработке';

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
        return [


        ];
    }
}
