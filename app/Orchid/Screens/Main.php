<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Action;
use Orchid\Screen\Layout;
use Orchid\Screen\Screen;

class Main extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Админ панель чат-бота ВИТЕКС';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Привет!';

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
