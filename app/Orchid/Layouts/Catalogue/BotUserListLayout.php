<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Catalogue;


use App\Models\BotUser;
use App\Models\Region;
use Orchid\Platform\Models\User;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class BotUserListLayout extends Table
{
    /**
     * @var string
     */
    public $target = 'botusers';

    /**
     * @return array
     */
    public function columns(): array
    {
        return [

            TD::set('msng_firstname', __('Имя в мессенджере'))
                ->sort()
                ->cantHide()
                ->filter(TD::FILTER_TEXT),

            TD::set('name', __('Имя'))
                ->sort()
                ->cantHide()
                ->filter(TD::FILTER_TEXT),

            TD::set('msng_id', __('msng_id'))
                ->sort()
                ->cantHide()
                ->filter(TD::FILTER_TEXT),

            TD::set('region_id', __('Pегион'))
                ->sort()
                ->cantHide()
                ->render(function (BotUser $user) {
                    $name = Region::query()->where('id', '=', $user->region_id)->pluck('name')->first();
                    return $name ?? '-';
                })
            ,

            TD::set('created_at', __('Дата создания'))
                ->sort()
                ->render(function (BotUser $botUser) {
                    return $botUser->created_at->toDateTimeString();
                }),

            TD::set('message', 'Сообщение')
                ->align('center')
                ->width('50')
                ->render(function (BotUser $botUser) {
                    return ModalToggle::make('')
                        ->modal('SendSingleMessageModal')
                        ->parameters(['botuser_id' => $botUser->id, 'msng_id' => $botUser->MessengerId()])
                        ->method('SendSingleMessage')
                        ->icon('speech');
                }),


        ];
    }
}
