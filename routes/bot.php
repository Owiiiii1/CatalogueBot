<?php

use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Cache\LaravelCache;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\Drivers\Telegram\TelegramDriver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * Route for telegram bot
 */
Route::post('telegrambot', function (Request $request){

    /**
     * InlineQuery
     */


    /**
     *  Бот
     */
    $controller = new \App\Http\Controllers\TelegramBotController();
    $controller->handle($request);
});

/**
 * //ToDo NO IMPLEMENTATION
 * Route for Viber bot
 */

//Route::match(['get', 'post'], '/viberbot', function (Request $request){
//
//    $controller = new \App\Http\Controllers\ViberBotController();
//    $controller->handle($request);
//
//});
