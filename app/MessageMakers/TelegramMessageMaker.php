<?php


namespace App\MessageMakers;


class TelegramMessageMaker
{
    public static function GetGreetingsMessage($lang = 'ru')
    {
        $message = "";

        if($lang == 'ukr')
        {
            $message = 'Приветствие';
        }
        elseif ($lang == 'ru')
        {
            $message =

                'Здравствуйте!'."\n"."\n".

                'Даний бот допоможе Вам вирішити наступні питання:'."\n"."\n".

                '   ✅ Подивитися асортимент'."\n".
                '   ✅ Дізнатися роздрібну ціну'."\n".
                '   ✅ Дізнатися чи є товар в наявності'."\n".
                '   ✅ Дізнатися контакти менеджерів'."\n".
                '   ✅ Дізнатися поточні акції'."\n".
                '   ✅ Дати відповідь на інші Ваші запитання'."\n".
                '   ✅ Дізнатися актуальний курс валют'


            ;
        }
        return $message;
    }

    public static function GetTelegramCategoriesMessage($lang = 'ru')
    {
        $message = "";

        if($lang == 'ukr')
        {
            $message = '111';
        }
        elseif ($lang == 'ru')
        {
            $message =
                'Виберіть категорію продукції';
        }
        return $message;
    }

    public static function GetTelegramStocksMessage($lang = 'ru')
    {
        $message = "";

        if($lang == 'ukr')
        {
            $message = '111';
        }
        elseif ($lang == 'ru')
        {
            $message =
                'Виберіть файл з акціями для даної категорії:';
        }
        return $message;
    }

    public static function GetTelegramPriceFailMessage($lang = 'ru')
    {
        $message = "";

        if($lang == 'ukr')
        {
            $message = '111';
        }
        elseif ($lang == 'ru')
        {
            $message =
                'У даному розділі прайс відсутній. Будь ласка, виберіть інший розділ.'
            ;
        }
        return $message;
    }

    public static function GetTelegramCertificateFailMessage($lang = 'ru')
    {
        $message = "";

        if($lang == 'ukr')
        {
            $message = '111';
        }
        elseif ($lang == 'ru')
        {
            $message =
                'У даному розділі сертифікат відсутній. Будь ласка, виберіть інший розділ.';
        }
        return $message;
    }

    public static function GetTelegramStocksFailMessage($lang = 'ru')
    {
        $message = "";

        if($lang == 'ukr')
        {
            $message = '111';
        }
        elseif ($lang == 'ru')
        {
            $message =
                'По даній групі товарів акції відсутні.';
        }
        return $message;
    }

    public static function GetTelegramBrandsMessage($lang = 'ru')
    {
        $message = "";

        if($lang == 'ukr')
        {
            $message = '111';
        }
        elseif ($lang == 'ru')
        {
            $message =
                'Виберіть бренд:';
        }
        return $message;
    }

    public static function GetTelegramCollectionsMessage($lang = 'ru')
    {
        $message = "";

        if($lang == 'ukr')
        {
            $message = '111';
        }
        elseif ($lang == 'ru')
        {
            $message =
                'Виберіть колекцію:';
        }
        return $message;
    }


    public static function GetWEBMessage($lang = 'ru')
    {
        $message = "";

        if($lang == 'ru')
        {
            $message = '🔻Відвідайте наш сайт🔻';
        }

        return $message;
    }

    public static function GetTelegramCertificatesMessage($lang = 'ru')
    {
        $message = "";

        if($lang == 'ukr')
        {
            $message = '111';
        }
        elseif ($lang == 'ru')
        {
            $message =
                'Виберіть сертифікат для завантаження:';
        }
        return $message;
    }


    public static function GetTelegramFinishQuestionMessage($phone, $question, $region, $manager, $lang = 'ru')
    {
        $message = "";

        if($lang == 'ukr')
        {
            $message = '111';
        }
        elseif ($lang == 'ru')
        {
            $message =
                'Дякуємо! Ваше питання дуже важливе для нас!'."\n\n".

                'Ваш регіон:  '.$region->name."\n\n".

                'Ваш телефон:  '.$phone."\n\n".

                'Ваш менеджер:  '.$manager->name."\n\n".

                'Ваше запитання:'."\n".
                '"'.$question.'"'."\n\n".

                'Ми зв\'яжемося з Вами найближчим часом.'


            ;
        }
        return $message;
    }

    public static function SendMessageToManager($phone, $question, $name)
    {

            $message =

                'Поступил вопрос:'."\n\n".

                '"'.$question.'"'."\n\n".

                'Телефон клиента:  '.$phone."\n\n".
                'Имя клиента:  '. $name

            ;

        return $message;
    }

    public static function GetExchangeMessage($usd, $eu, $euNBU, $lang = 'ru')
    {
        $message = "";

        if($lang == 'ukr')
        {
            $message = '111';
        }
        elseif ($lang == 'ru')
        {
            $message =
                'Актуальні курси валют на сьогодні:'. "\n\n"
            .'Долар США готівковий:  '.$usd.'грн'. "\n"
            .'Евро готівковий:  '.$eu.'грн'. "\n"
            .'Евро НБУ:  '.$euNBU.'грн'
            ;
        }
        return $message;
    }

}
