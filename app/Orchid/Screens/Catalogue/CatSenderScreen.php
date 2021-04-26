<?php

namespace App\Orchid\Screens\Catalogue;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Orchid\Alert\Toast;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Matrix;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Layout;
use Orchid\Screen\Layouts\Modal;
use Orchid\Screen\Screen;

class CatSenderScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Рассылка';

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
        return [
            ModalToggle::make('Создать рассылку')
                ->modal('CreateBulkMailingAsyncModal')
                ->modalTitle('Создание новой рассылки пользователям')
                ->method('createBulkMailing')
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
        return [

            \Orchid\Support\Facades\Layout::modal('CreateBulkMailingAsyncModal',
                [
                    \Orchid\Support\Facades\Layout::rows(
                        [

                            TextArea::make('message.text')
                            ->rows(7),

                            Matrix::make('message.keyboard')
                                ->title('Клавиатура')
                                ->columns(['text', 'url'])
                                ->fields([
                                    'text'=> Input::make('button.text'),
                                    'url' => Input::make('button.url'),
                                ]),
                        ]),

                ])->size(Modal::SIZE_LG),

        ];
    }


    public function createBulkMailing(Request $request)
    {

        Log::info(print_r($request->input(), true));
    }

}
