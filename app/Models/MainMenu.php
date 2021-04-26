<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class MainMenu extends Model
{
    use AsSource;

    /**
     * @var array
     */
    protected $guarded = [];
}
