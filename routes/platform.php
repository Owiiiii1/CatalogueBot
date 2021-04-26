<?php

declare(strict_types=1);

use App\Orchid\Screens\Catalogue\CatBotUsersScreen;
use App\Orchid\Screens\Catalogue\CatBrandsScreen;
use App\Orchid\Screens\Catalogue\CatCategoriesScreen;
use App\Orchid\Screens\Catalogue\CatCertificateScreen;
use App\Orchid\Screens\Catalogue\CatCollectionsScreen;
use App\Orchid\Screens\Catalogue\CatItemsScreen;
use App\Orchid\Screens\Catalogue\CatLocationScreen;
use App\Orchid\Screens\Catalogue\CatManagerScreen;
use App\Orchid\Screens\Catalogue\CatOrchidUsersScreen;
use App\Orchid\Screens\Catalogue\CatPricesScreen;
use App\Orchid\Screens\Catalogue\CatSenderScreen;
use App\Orchid\Screens\Catalogue\CatSettingsScreen;
use App\Orchid\Screens\Catalogue\CatStocksScreen;
use App\Orchid\Screens\Catalogue\CatStorageScreen;
use App\Orchid\Screens\Catalogue\EditMenuScreen;
use App\Orchid\Screens\Examples\ExampleCardsScreen;
use App\Orchid\Screens\Examples\ExampleChartsScreen;
use App\Orchid\Screens\Examples\ExampleFieldsAdvancedScreen;
use App\Orchid\Screens\Examples\ExampleFieldsScreen;
use App\Orchid\Screens\Examples\ExampleLayoutsScreen;
use App\Orchid\Screens\Examples\ExampleScreen;
use App\Orchid\Screens\Examples\ExampleTextEditorsScreen;
use App\Orchid\Screens\Main;
use App\Orchid\Screens\Role\RoleEditScreen;
use App\Orchid\Screens\Role\RoleListScreen;
use App\Orchid\Screens\User\UserEditScreen;
use App\Orchid\Screens\User\UserListScreen;
use App\Orchid\Screens\User\UserProfileScreen;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the need "dashboard" middleware group. Now create something great!
|
*/

// Main
Route::screen('/main', Main::class)
    ->name('platform.main');

// Platform > Profile
Route::screen('profile', UserProfileScreen::class)
    ->name('platform.profile')
   ;

// Platform > System > Users
Route::screen('users/{users}/edit', UserEditScreen::class)
    ->name('platform.systems.users.edit')
   ;

// Platform > System > Users > Create
Route::screen('users/create', UserEditScreen::class)
    ->name('platform.systems.users.create')
   ;

// Platform > System > Users > User
Route::screen('users', UserListScreen::class)
    ->name('platform.systems.users')
    ;

// Platform > System > Roles > Role
Route::screen('roles/{roles}/edit', RoleEditScreen::class)
    ->name('platform.systems.roles.edit')
    ;

// Platform > System > Roles > Create
Route::screen('roles/create', RoleEditScreen::class)
    ->name('platform.systems.roles.create')
   ;

// Platform > System > Roles
Route::screen('roles', RoleListScreen::class)
    ->name('platform.systems.roles')
    ;

// Example...
Route::screen('example', ExampleScreen::class)
    ->name('platform.example')
   ;

Route::screen('example-fields', ExampleFieldsScreen::class)->name('platform.example.fields');
Route::screen('example-layouts', ExampleLayoutsScreen::class)->name('platform.example.layouts');
Route::screen('example-charts', ExampleChartsScreen::class)->name('platform.example.charts');
Route::screen('example-editors', ExampleTextEditorsScreen::class)->name('platform.example.editors');
Route::screen('example-cards', ExampleCardsScreen::class)->name('platform.example.cards');
Route::screen('example-advanced', ExampleFieldsAdvancedScreen::class)->name('platform.example.advanced');

//роуты каталога
//________________________________________________________________________________________________________________

Route::screen('cat-location', CatLocationScreen::class)->name('platform.cat.location');
Route::screen('cat-botusers', CatBotUsersScreen::class)->name('platform.cat.botusers');
Route::screen('cat-users', CatOrchidUsersScreen::class)->name('platform.cat.users');
Route::screen('cat-categories', CatCategoriesScreen::class)->name('platform.cat.categories');
Route::screen('cat-managers', CatManagerScreen::class)->name('platform.cat.managers');

Route::screen('cat-mainmenu', EditMenuScreen::class)->name('platform.cat.mainmenu');
Route::screen('cat-stocks', CatStocksScreen::class)->name('platform.cat.stocks');
Route::screen('cat-storage', CatStorageScreen::class)->name('platform.cat.storage');
Route::screen('cat-sender', CatSenderScreen::class)->name('platform.cat.sender');
Route::screen('cat-settings', CatSettingsScreen::class)->name('platform.cat.settings');
Route::screen('cat-prices', CatPricesScreen::class)->name('platform.cat.prices');

Route::screen('cat-certificates', CatCertificateScreen::class)->name('platform.cat.certificates');

Route::screen('cat-brands', CatBrandsScreen::class)->name('platform.cat.brands');
Route::screen('cat-collections', CatCollectionsScreen::class)->name('platform.cat.collections');
Route::screen('cat-items', CatItemsScreen::class)->name('platform.cat.items');

//Route::screen('idea', 'Idea::class','platform.screens.idea');
