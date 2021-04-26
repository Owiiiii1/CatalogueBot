<?php

namespace App\Orchid\Screens\Catalogue;

use App\Models\Category;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Upload;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Toast;

class CatCertificateScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Сертификаты';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Давайте посмотрим что тут у нас есть...';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'certificates' => Certificate::all()
        ];
    }

    /**
     * Button commands.
     *
     * @return Action[]
     */
    public function commandBar(): array
    {
        return [ ModalToggle::make('Добавить сертификат')
            ->modal('oneAsyncModal')
            ->modalTitle('Создание нового сертификата')
            ->method('saveCertificate')
            ->icon('plus'),];
    }

    /**
     * Views.
     *
     * @return Layout[]
     */
    public function layout(): array
    {
        return [


            \Orchid\Support\Facades\Layout::table('certificates', [

                TD::set('category_id', __('Категория'))
                    ->sort()
                    ->width('80px')
                    ->cantHide()
                    ->render(function (Certificate $certif) {
                        return Str::limit($certif->category->name, 200);
                    })

                ,

                TD::set('name', __('Название сертификата'))
                    ->sort()
                    ->width('130px')
                    ->cantHide()


                ,

                TD::set('description', __('Описание'))
                    ->sort()
                    ->width('100px')
                    ->cantHide()
                ,

                TD::set('id', __('Файл сертификата'))
                    ->sort()
                    ->width('150px')
                    ->cantHide()
                    ->render(function (Certificate $certif){
                        $fileURL = $certif->attachment()->first();

                    })
                ,

                TD::set('created_at', __('Дата создания'))
                    ->sort()
                    ->render(function (Certificate $certif) {
                        return $certif->created_at->toDateTimeString();
                    }),

                TD::set('settings', 'Настройки')
                    ->align(TD::ALIGN_CENTER)
                    ->width('100px')
                    ->render(function (Certificate $certif) {
                        return DropDown::make()
                            ->icon('options-vertical')
                            ->list([

                                ModalToggle::make('Изменить')
                                    ->modal('oneAsyncModal')
                                    ->modalTitle($certif->name)
                                    ->method('editCertificate')
                                    ->asyncParameters([
                                        'certificates' => $certif->id,
                                    ])
                                    ->icon('pencil'),

                                Button::make(__('Удалить'))
                                    ->method('remove')
                                    ->confirm(__('Вы уверены, что хотите удалить сертификат?'))
                                    ->parameters([
                                        'id' => $certif->id,
                                    ])
                                    ->icon('trash'),
                            ]);
                    }),
            ]),

            Layout::modal('oneAsyncModal',
                [
                    Layout::rows([

                        Select::make('certificates.category.id')
                            ->fromModel(Category::class, 'name')
                            ->value('certificates.category.id')
                            ->required()
                            ->title('Категория')
                            ->help('Выберите категорию продукции'),

                        Input::make('certificates.name')
                            ->type('text')
                            ->required()
                            ->title(__('Наименование сертификата'))
                            ->placeholder(__('Укажите наименование сертификата')),

                        Input::make('certificates.description')
                            ->type('text')
                            ->title(__('Описание сертификата'))
                            ->placeholder(__('Укажите описание сертификата')),

                        Upload::make('certificates.attachment')
                            ->title(__('Файл сертификата'))
                            ->acceptedFiles('image/*,application/pdf,.psd')
                            ->placeholder(__('Добавьте файл'))
                    ]),

                ])->async('asyncGetCertificates')
        ];
    }

    public function asyncGetCertificates(Certificate $certif): array
    {
        return [
            'certificates' => $certif

        ];
    }

    public function saveCertificate(Request $request)
    {

        $cert = Certificate::CreateNew($request->input('certificates.name'), $request->input('certificates.description'), $request->input('certificates.category.id'));

        $cert->attachment()->syncWithoutDetaching($request->input('certificates.attachment', [])
        );

        Toast::info(__('Сертификат сохранен.'));
    }

    public function editCertificate(Certificate $certif, Request $request)
    {

        $certif->name = $request->input('certificates.name');
        $certif->description = $request->input('certificates.description');
        $certif->category_id = $request->input('certificates.category.id');

        $certif->attachment()->syncWithoutDetaching($request->input('certificates.attachment', []));

        $certif->save();

        Toast::info(__('Сертификат сохранен.'));
    }

    public function remove(Request $request)
    {
        $certif = Certificate::query()->findOrFail($request->get('id'));

        $certif->attachment()->each(function ($item){$item->delete();});

        $certif->delete();

        Toast::info(__('Сертификат удален'));
    }
}
