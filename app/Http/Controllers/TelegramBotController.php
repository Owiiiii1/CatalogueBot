<?php


namespace App\Http\Controllers;


use App\Conversations\NewQuestionConversation;
use App\Conversations\NewRemainingConversation;
use App\Conversations\SelectRegionConversation;
use App\Conversations\SendOrderConversation;
use App\KeyboardMakers\TelegramKeyboardMaker;
use App\MessageMakers\TelegramMessageMaker;
use App\Models\BotUser;
use App\Models\Brand;
use App\Models\BrandsCategory;
use App\Models\Buttons;
use App\Models\Category;
use App\Models\Certificate;
use App\Models\Collection;
use App\Models\Price;
use App\Models\Region;
use App\Models\Settings;
use App\Models\Stocks;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Cache\LaravelCache;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\BotMan\Messages\Attachments\File;
use BotMan\BotMan\Messages\Attachments\Image;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
use BotMan\Drivers\Telegram\TelegramDriver;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TelegramBotController
{
    public function handle(Request $request)
    {
        try {


            /***************************************************************************************************************
             * Формируем конфиг для бота
             *
             **************************************************************************************************************/

            $config =
                [
                    'telegram' =>
                        [
                            'token' => env('TELEGRAM_TOKEN')
                        ]
                ];

            /***************************************************************************************************************
             * Подгружаем нужные драйвера
             *
             **************************************************************************************************************/
            DriverManager::loadDriver(TelegramDriver::class);

            /***************************************************************************************************************
             * Создаем экземпляр + подключаем кеш
             *
             **************************************************************************************************************/
            $botman = BotManFactory::create($config, new LaravelCache());

            $botman->hears('/start{referrer}', function (BotMan $bot, $referrer) {

                $user = BotUser::GetByMessengerId($bot->getUser()->getId());

                $buttons = Buttons::query()->where('is_active', '=', true)->get();

                if ($user) {
                    $bot->reply(
                        TelegramMessageMaker::GetGreetingsMessage(),
                        TelegramKeyboardMaker::GetTelegramPersistentKeyboard($buttons)
                    );
                }
                else
                {
                    BotUser::CreateNew(
                        'Telegram',
                        $bot->getUser()->getId(),
                        $bot->getUser()->getUsername(),
                        $bot->getUser()->getFirstName(),
                        $referrer
                    );

                    $bot->startConversation(new SelectRegionConversation());

                }

            })->stopsConversation();

            $botman->hears('Отмена', function (BotMan $bot) {

                $user = BotUser::GetByMessengerId($bot->getUser()->getId());

                $buttons = Buttons::query()->where('is_active', '=', true)->get();

                    $bot->reply(
                        'Главное меню',
                        TelegramKeyboardMaker::GetTelegramPersistentKeyboard($buttons)
                    );

            })->stopsConversation();

            ///////////////////////////////////////////////////////////////////////////////////////////////
            /// Секция главного меню
            ///////////////////////////////////////////////////////////////////////////////////////////////

            $botman->hears('(.*)Каталог', function (BotMan $bot) {

                $ctg = Category::all();

                $bot->reply(
                        TelegramMessageMaker::GetTelegramCategoriesMessage(),
                        TelegramKeyboardMaker::GetTelegramCategoriesKeyboard($ctg)
                    );

            })->stopsConversation();

            $botman->hears('(.*)Прайс', function (BotMan $bot) {

                $ctg = Category::all();

                $bot->reply(
                    TelegramMessageMaker::GetTelegramCategoriesMessage(),
                    TelegramKeyboardMaker::GetTelegramPriceCategoriesKeyboard($ctg)
                );

            })->stopsConversation();

            $botman->hears('(.*)Акції', function (BotMan $bot) {

                $ctg = Category::all();

                $bot->reply(
                    TelegramMessageMaker::GetTelegramCategoriesMessage(),
                    TelegramKeyboardMaker::GetTelegramStocksCategoriesKeyboard($ctg)
                );

            })->stopsConversation();

            $botman->hears('(.*)Залишки товару', function (BotMan $bot) {

                $user = BotUser::GetByMessengerId($bot->getUser()->getId());

                if ($user->region_id > 0)
                {
                    $bot->startConversation( new NewRemainingConversation());
                }
                else
                {
                    $bot->startConversation( new SelectRegionConversation());
                }


            })->stopsConversation();

            $botman->hears('(.*)Задати питання', function (BotMan $bot) {

                $user = BotUser::GetByMessengerId($bot->getUser()->getId());

                if ($user->region_id > 0)
                {
                    $bot->startConversation( new NewQuestionConversation());
                }
                else
                {
                    $bot->startConversation( new SelectRegionConversation());
                }



            })->stopsConversation();

            $botman->hears('(.*)Контакти', function (BotMan $bot) {

                $contacts = Settings::query()->where('name', '=', 'contacts')->first();

                $bot->reply($contacts->unit1);

        })->stopsConversation();

            $botman->hears('(.*)Сайт', function (BotMan $bot) {

                $www = Settings::query()->where('name', '=', 'www')->first();

                $bot->reply(
                    TelegramMessageMaker::GetWEBMessage(),
                    TelegramKeyboardMaker::GetTelegramWEBKeyboard($www->unit1)
                );

            })->stopsConversation();

            $botman->hears('(.*)Сертифікати', function (BotMan $bot) {

                $ctg = Category::all();

                $bot->reply(
                    TelegramMessageMaker::GetTelegramCategoriesMessage(),
                    TelegramKeyboardMaker::GetTelegramCertificatesCategoriesKeyboard($ctg)
                );

            })->stopsConversation();

            $botman->hears('(.*)Курс валют', function (BotMan $bot) {

                $usd = Settings::query()->where('name', '=', 'exchange_usd')->first();
                $eu = Settings::query()->where('name', '=', 'exchange_eu')->first();
                $euNBU = Settings::query()->where('name', '=', 'exchange_euNBU')->first();

                $bot->reply(
                    TelegramMessageMaker::GetExchangeMessage($usd->unit1, $eu->unit1, $euNBU->unit1)
                );

            })->stopsConversation();

            ////////////////////////////////////////////////////////////////////////////////////////////////////////
            /// Секция каталога
            ///////////////////////////////////////////////////////////////////////////////////////////////////////

            $botman->hears('category_button_{category}', function (BotMan $bot, $catID)
            {

                $bot->userStorage()->save(['categoryID' => $catID]);

                $brandID = BrandsCategory::query()->where('category_id', '=', $catID)->pluck('brand_id')->values()->toArray();

                $brands = Brand::query()->whereIn('id', $brandID)->get();

                $bot->reply(
                    TelegramMessageMaker::GetTelegramBrandsMessage(),
                    TelegramKeyboardMaker::GetTelegramBrandsKeyboard($brands)
                );
            });

            $botman->hears('brand_button_{brand}', function (BotMan $bot, $brandID)
            {
                $bot->userStorage()->save([
                    'brandID' => $brandID
                ]);

                $cat = Category::query()->where('id', '=', $bot->userStorage()->get('categoryID'))->pluck('name')->values()->toArray();

                if ($bot->userStorage()->get('categoryID') == '6')
                {
                    $collections = Collection::query()->where('brand_id', '=', $brandID)->get();

                    $bot->reply(
                        TelegramMessageMaker::GetTelegramCollectionsMessage(),
                        TelegramKeyboardMaker::GetTelegramCollectionsKeyboard($collections)
                    );
                }
                else
                {
                    $brand = Brand::query()->where('id', '=', $brandID)->first();
                    $attach = $brand->attachment()->get();

                    foreach ($attach as $item)
                    {
                        $attachment = new File($item->url);

                        $message = OutgoingMessage::create('')
                            ->withAttachment($attachment);

                        $bot->reply($message);
                    }
                }
            });

            $botman->hears('collection_button_{col}', function (BotMan $bot, $colID)
            {

                try {
                    $collection = Collection::query()->where('id', '=', $colID)->first();
                    $attach = $collection->attachment()->first();

                    if($attach)
                    {
                        $attachment = new File(url()->asset($attach->url()));
                        $message = OutgoingMessage::create(' ')
                            ->withAttachment($attachment);

                        $bot->reply($message);
                    }
                    else
                    {
                        $bot->reply('Каталог на обновлении, мы добавим файл для загрузки в ближайшее время.');
                    }
                    //Log::info($attach->url());

                }
                catch (Exception $ex)
                {
                    $bot->reply('Каталог на обновлении, мы добавим файл для загрузки в ближайшее время.');
                }


            });

            ////////////////////////////////////////////////////////////////////////////////////////////////////////
            /// Секция прайсов
            ///////////////////////////////////////////////////////////////////////////////////////////////////////

            $botman->hears('price_category_button_{category}', function (BotMan $bot, $categoryID)
            {

                $price = Price::query()->where('cat_id', '=', $categoryID)->first();

                if ($price) {

                    $attach = $price->attachment()->get();

                    if (count($attach))
                    {
                        foreach ($attach as $item)
                        {
                            $attachment = new File($item->url);

                            $message = OutgoingMessage::create('')
                                ->withAttachment($attachment);

                            $bot->reply($message);
                        }
                    }
                    else
                    {
                        $bot->reply(TelegramMessageMaker::GetTelegramPriceFailMessage());
                    }




                } else {
                    $bot->reply(TelegramMessageMaker::GetTelegramPriceFailMessage());
                }
            });

            ////////////////////////////////////////////////////////////////////////////////////////////////////////
            /// Секция акции
            ///////////////////////////////////////////////////////////////////////////////////////////////////////

            $botman->hears('stocks_category_button_{category}', function (BotMan $bot, $catID)
            {

                $stocks = Stocks::query()->where('cat_id', '=', $catID)->get();

                if (count($stocks)>0)
                {
                    $bot->reply(
                        TelegramMessageMaker::GetTelegramStocksMessage(),
                        TelegramKeyboardMaker::GetTelegramStocksKeyboard($stocks)
                    );
                }
                else
                {
                    $bot->reply(TelegramMessageMaker::GetTelegramStocksFailMessage());
                }
            });

            $botman->hears('stocksID_{item}', function (BotMan $bot, $stocksID)
            {
                $stocks = Stocks::query()->where('id', '=', $stocksID)->first();
                $attach = $stocks->attachment()->get();

                if (count($attach)) {

                    foreach ($attach as $item) {
                        $attachment = new File($item->url);

                        $message = OutgoingMessage::create('')
                            ->withAttachment($attachment);

                        $bot->reply($message);
                    }
                }
                else
                {
                    $bot->reply(TelegramMessageMaker::GetTelegramStocksFailMessage());
                }

            });

            ////////////////////////////////////////////////////////////////////////////////////////////////////////
            /// Секция сертификатов
            ///////////////////////////////////////////////////////////////////////////////////////////////////////

            $botman->hears('certificates_category_button_{category}', function (BotMan $bot, $catID)
            {

               $certificates = Certificate::query()->where('category_id', '=', $catID)->get();

                if (count($certificates) > 0)
                {
                    $bot->reply(
                        TelegramMessageMaker::GetTelegramCertificatesMessage(),
                        TelegramKeyboardMaker::GetTelegramCertificatesKeyboard($certificates)
                    );
                }
                else
                {
                    $bot->reply(
                        TelegramMessageMaker::GetTelegramCertificateFailMessage()
                    );
                }
            });

            $botman->hears('certificateID_{item}', function (BotMan $bot, $certifID)
            {
                $certif = Certificate::query()->where('id', '=', $certifID)->first();

                $attach = $certif->attachment()->get();

                foreach ($attach as $item)
                {
                    $attachment = new File($item->url);

                    $message = OutgoingMessage::create('')
                        ->withAttachment($attachment);

                    $bot->reply($message);
                }

            });

            ////////////////////////////////////////////////////////////////////////////////////////////////////////
            /// Секция прочее
            ///////////////////////////////////////////////////////////////////////////////////////////////////////

            $botman->hears('sendOrder_{item}', function (BotMan $bot, $item)
            {

                $bot->startConversation(new SendOrderConversation($item));

            });

            $botman->listen();

        }
        catch (\Exception $ex)
        {
            Log::error($ex->getFile().'- '.$ex->getLine().' - '.$ex->getMessage());
        }
    }
}

