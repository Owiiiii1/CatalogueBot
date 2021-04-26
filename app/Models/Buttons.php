<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Buttons extends Model
{
    use AsSource;

    public $timestamps = false;

    /**
     * @var array
     */
    protected $guarded = [];
}
