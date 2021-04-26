<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Screen\AsSource;

class Settings extends Model
{
    use AsSource, Attachable;

    /**
     * @var array
     */
    protected $guarded = [];

    public function get($key)
    {
        $value = Settings::query()->where('name', '=', $key)->first();

        return $value;
    }

    public function set($key, $newValue)
    {
        $value = Settings::query()->where('name', '=', $key)->first();
        $value->unit1 = $newValue;
        $value->save();
    }
}
