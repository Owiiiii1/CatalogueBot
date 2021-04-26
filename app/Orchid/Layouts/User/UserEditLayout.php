<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\User;

use App\Orchid\Layouts\Role\RolePermissionLayout;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;

class UserEditLayout extends Rows
{
    /**
     * Views.
     *
     * @return array
     */
    public function fields(): array
    {
        return [
            Input::make('user.name')
                ->type('text')
                ->max(255)
                ->required()
                ->title(__('Name'))
                ->placeholder(__('Name')),

            Input::make('user.email')
                ->type('email')
                ->required()
                ->title(__('Email'))
                ->placeholder(__('Email')),

            Input::make('user.password')
                ->type('password')
                ->required()
                ->title(__('Password'))
                ->placeholder(__('Password')),
        ];
    }
}
