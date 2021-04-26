<?php


namespace App\KeyboardMakers;


use BotMan\Drivers\Telegram\Extensions\Keyboard;
use BotMan\Drivers\Telegram\Extensions\KeyboardButton;

class TelegramKeyboardMaker
{
    public static function GetTelegramPersistentKeyboard($menu, $lang = 'ru')
    {
        $keyboard = Keyboard::create()
            ->type( Keyboard::TYPE_KEYBOARD )
            ->oneTimeKeyboard(false)
            ->resizeKeyboard(true);

            for ($i = 1; $i<=count($menu); $i++)
        {
            $buttons[] =  KeyboardButton::create($menu[$i-1]->image.$menu[$i-1]->name);
            if($i % 3 == 0)
            {
                $keyboard->addRow(... $buttons);
                $buttons = [];
            }
        }

        $keyboard->addRow(... $buttons);

        return $keyboard->toArray();


    }

    public static function GetTelegramCategoriesKeyboard($ctg)
    {
        $keyboard = Keyboard::create()
            ->type( Keyboard::TYPE_INLINE )
            ->oneTimeKeyboard(false)
            ->resizeKeyboard(true);

        foreach ($ctg as $cat)
        {
            $keyboard->addRow(KeyboardButton::create($cat->image . $cat->name)->callbackData('category_button_'.$cat->id));
        }

        return $keyboard->toArray();
    }

    public static function GetSelectRegionsKeyboard($regions)
    {
        $keyboard = Keyboard::create()
            ->type( Keyboard::TYPE_INLINE )
            ->oneTimeKeyboard(false)
            ->resizeKeyboard(true);

        foreach ($regions as $region)
        {
            $keyboard->addRow(KeyboardButton::create($region->name)->callbackData($region->id));
        }

        return $keyboard->toArray();
    }

    public static function GetTelegramPriceCategoriesKeyboard($ctg)
    {
        $keyboard = Keyboard::create()
            ->type( Keyboard::TYPE_INLINE )
            ->oneTimeKeyboard(false)
            ->resizeKeyboard(true);

        foreach ($ctg as $cat)
        {
            $keyboard->addRow(KeyboardButton::create($cat->image . $cat->name)->callbackData('price_category_button_'.$cat->id));
        }

        return $keyboard->toArray();
    }

    public static function GetTelegramStocksCategoriesKeyboard($ctg)
    {
        $keyboard = Keyboard::create()
            ->type( Keyboard::TYPE_INLINE )
            ->oneTimeKeyboard(false)
            ->resizeKeyboard(true);

        foreach ($ctg as $cat)
        {
            $keyboard->addRow(KeyboardButton::create($cat->image . $cat->name)->callbackData('stocks_category_button_'.$cat->id));
        }

        return $keyboard->toArray();
    }

    public static function GetTelegramRemainingRegionsKeyboard($ctg)
    {
        $keyboard = Keyboard::create()
            ->type( Keyboard::TYPE_INLINE )
            ->oneTimeKeyboard(false)
            ->resizeKeyboard(true);

        foreach ($ctg as $cat)
        {
            $keyboard->addRow(KeyboardButton::create($cat->image . $cat->name)->callbackData('remaining_region_button_'.$cat->id));
        }

        return $keyboard->toArray();
    }

    public static function GetTelegramCertificatesCategoriesKeyboard($ctg)
    {
        $keyboard = Keyboard::create()
            ->type( Keyboard::TYPE_INLINE )
            ->oneTimeKeyboard(false)
            ->resizeKeyboard(true);

        foreach ($ctg as $cat)
        {
            $keyboard->addRow(KeyboardButton::create($cat->image . $cat->name)->callbackData('certificates_category_button_'.$cat->id));
        }

        return $keyboard->toArray();
    }

    public static function GetTelegramCertificatesKeyboard($names)
    {
        $keyboard = Keyboard::create()
            ->type( Keyboard::TYPE_INLINE )
            ->oneTimeKeyboard(false)
            ->resizeKeyboard(true);

        foreach ($names as $name)
        {
            $keyboard->addRow(KeyboardButton::create($name->name)->callbackData('certificateID_'.$name->id));
        }

        return $keyboard->toArray();
    }

    public static function GetTelegramStocksKeyboard($names)
    {
        $keyboard = Keyboard::create()
            ->type( Keyboard::TYPE_INLINE )
            ->oneTimeKeyboard(false)
            ->resizeKeyboard(true);

        foreach ($names as $name)
        {
            $keyboard->addRow(KeyboardButton::create($name->name)->callbackData('stocksID_'.$name->id));
        }

        return $keyboard->toArray();
    }

    public static function GetTelegramWEBKeyboard($www, $lang = 'ru')
    {
        $keyboard = Keyboard::create()->type( Keyboard::TYPE_INLINE )
            ->oneTimeKeyboard(false)
            ->resizeKeyboard()

            ->addRow(
                KeyboardButton::create($www)->url($www)
            )
            ->toArray();

        return $keyboard;

    }

    public static function GetTelegramPriceCitiesKeyboard($cities)
    {
        $keyboard = Keyboard::create()
            ->type( Keyboard::TYPE_INLINE )
            ->oneTimeKeyboard(false)
            ->resizeKeyboard(true);

        foreach ($cities as $city)
        {
            $keyboard->addRow(KeyboardButton::create($city->image . $city->name)->callbackData('price_city_button_'.$city->id));
        }

        return $keyboard->toArray();
    }

    public static function GetTelegramStocksCitiesKeyboard($cities)
    {
        $keyboard = Keyboard::create()
            ->type( Keyboard::TYPE_INLINE )
            ->oneTimeKeyboard(false)
            ->resizeKeyboard(true);

        foreach ($cities as $city)
        {
            $keyboard->addRow(KeyboardButton::create($city->image . $city->name)->callbackData('stocks_city_button_'.$city->id));
        }

        return $keyboard->toArray();
    }

    public static function GetTelegramBrandsKeyboard($brands)
    {
        $keyboard = Keyboard::create()
            ->type( Keyboard::TYPE_INLINE )
            ->oneTimeKeyboard(false)
            ->resizeKeyboard(true);

        foreach ($brands as $brand)
        {
            $keyboard->addRow(KeyboardButton::create($brand->image . $brand->name)->callbackData('brand_button_'.$brand->id));
        }

        return $keyboard->toArray();
    }

    public static function GetTelegramCollectionsKeyboard($cols)
    {
        $keyboard = Keyboard::create()
            ->type( Keyboard::TYPE_INLINE )
            ->oneTimeKeyboard(false)
            ->resizeKeyboard(true);

        foreach ($cols as $col)
        {
            $keyboard->addRow(KeyboardButton::create($col->image . $col->name)->callbackData('collection_button_'.$col->id));
        }

        return $keyboard->toArray();
    }

    public static function GetTelegramRegionsKeyboard($regions)
    {
        $keyboard = Keyboard::create()
            ->type( Keyboard::TYPE_INLINE )
            ->oneTimeKeyboard(false)
            ->resizeKeyboard(true);

        foreach ($regions as $region)
        {
            $keyboard->addRow(KeyboardButton::create($region->name)->callbackData($region->id));
        }

        return $keyboard->toArray();
    }

    public static function GetTelegramPhoneKeyboard($lang = 'ru')
    {

        $keyboard = Keyboard::create()
            ->type(Keyboard::TYPE_KEYBOARD)
            ->oneTimeKeyboard(true)
            ->resizeKeyboard()
            ->addRow(
                KeyboardButton::create($lang == 'ru' ? "Поделиться телефоном" : "Get Phone")->requestContact()
            )
            ->addRow(
                KeyboardButton::create($lang == 'ru' ? "Отмена" : "Cancel")
            )


            ->toArray();

        return $keyboard;
    }

    public static function SendOrderKeyboard($item)
    {
        $keyboard = Keyboard::create()
            ->type(Keyboard::TYPE_INLINE)
            ->oneTimeKeyboard()

            ->addRow(KeyboardButton::create('Замовити')->callbackData('sendOrder_'.$item));

        return $keyboard->toArray();
    }
}
