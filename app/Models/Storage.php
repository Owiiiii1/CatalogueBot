<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Storage extends Model
{
    use AsSource;

    /**
     * @var array
     */
    protected $guarded = [];

    public static function CreateNew($name, $active = 0)
    {
        $storage = Storage::query()->firstOrCreate([
            'name' => $name,
            'is_active' => $active,

        ]);

        return $storage;
    }


    public static function DeleteStorage($id)
    {
        $storage = Storage::query()->where('id', '=', $id)->first();
        $storage->delete();
    }

}
