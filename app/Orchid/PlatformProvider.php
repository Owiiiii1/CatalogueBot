<?php

namespace App\Orchid;

use Laravel\Scout\Searchable;
use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemMenu;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\OrchidServiceProvider;

class PlatformProvider extends OrchidServiceProvider
{
    /**
     * @param Dashboard $dashboard
     */
    public function boot(Dashboard $dashboard): void
    {
        parent::boot($dashboard);

        // ...
    }

    /**
     * @return ItemMenu[]
     */
    public function registerMainMenu(): array
    {
        return [

            ItemMenu::label('Пользователи')
                ->icon('monitor')
                ->route('platform.systems.users')
                ->title('Пользователи')
                ,

            ItemMenu::label('Клиенты')
                ->icon('handbag')
                ->route('platform.cat.botusers')
            ,

            ItemMenu::label('Менджеры')
                ->icon('handbag')
                ->route('platform.cat.managers')
            ,


            ItemMenu::label('Главное меню чатбота')
                ->icon('monitor')
                ->route('platform.cat.mainmenu')
                ->title('Меню чат-бота')
            ,

            ItemMenu::label('Регионы')
                ->title('Каталог')
                ->icon('map')
                ->route('platform.cat.location'),

            ItemMenu::label('Каталог')
                ->icon('briefcase')
                ->slug('cat-menu')
                ->childs(),

            //-----------------------------вложенное меню------------------------------

            ItemMenu::label('Категории')
                ->place('cat-menu')
                ->icon('grid')
                ->route('platform.cat.categories')
                ,

            ItemMenu::label('Бренды')
                ->place('cat-menu')
                ->icon('magnet')
                ->route('platform.cat.brands'),

            ItemMenu::label('Коллекции')
                ->place('cat-menu')
                ->icon('number-list')
                ->route('platform.cat.collections'),


            //-----------------------------конец вложенного меню------------------------------

            ItemMenu::label('Сертификаты')
                ->icon('diamond')
                ->route('platform.cat.certificates'),

            ItemMenu::label('Прайсы')
                ->icon('dollar')
                ->route('platform.cat.prices'),

            ItemMenu::label('Рассылки')
                ->title('Прочее')
                ->icon('envelope-letter')
                ->route('platform.cat.sender'),

            ItemMenu::label('Акции')
                ->icon('fire')
                ->route('platform.cat.stocks'),

            ItemMenu::label('Настройки компании')
                ->icon('friends')
                ->route('platform.cat.settings'),

            ItemMenu::label('Настройки складов')
                ->icon('friends')
                ->route('platform.cat.storage'),

        ];
    }

    /**
     * @return ItemMenu[]
     */
    public function registerProfileMenu(): array
    {
        return [
            ItemMenu::label('Profile')
                ->route('platform.profile')
                ->icon('user'),
        ];
    }

    /**
     * @return ItemMenu[]
     */
    public function registerSystemMenu(): array
    {
        return [
            ItemMenu::label(__('Access rights'))
                ->icon('lock')
                ->slug('Auth')
                ->active('platform.systems.*')
                ->permission('platform.systems.index')
                ->sort(1000),

            ItemMenu::label(__('Users'))
                ->place('Auth')
                ->icon('user')
                ->route('platform.systems.users')
                ->permission('platform.systems.users')
                ->sort(1000)
                ->title(__('All registered users')),

            ItemMenu::label(__('Roles'))
                ->place('Auth')
                ->icon('lock')
                ->route('platform.systems.roles')
                ->permission('platform.systems.roles')
                ->sort(1000)
                ->title(__('A Role defines a set of tasks a user assigned the role is allowed to perform. ')),
        ];
    }

    /**
     * @return ItemPermission[]
     */
    public function registerPermissions(): array
    {
        return [
            ItemPermission::group(__('Systems'))
                ->addPermission('platform.systems.roles', __('Roles'))
                ->addPermission('platform.systems.users', __('Users')),
        ];
    }

    /**
     * @return Searchable|string[]
     */
    public function registerSearchModels(): array
    {
        return [
            // ...Models
            // \App\Models\User::class
        ];
    }
}
