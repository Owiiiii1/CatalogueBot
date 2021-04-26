<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Manager extends Model
{
    use AsSource;

    protected $guarded = [];

    public static function CreateNew($name, $phone = null, $msng_id = null)
    {
        $manager = Manager::query()->firstOrCreate([
            'name' => $name,
            'phone' => $phone,
            'msng_id' => $msng_id,

        ]);

        return $manager;
    }

    /**
     * @param $id
     * @throws \Exception
     */
    public static function DeleteManager($id)
    {
        $manager = Manager::query()->where('id', '=', $id)->first();
        $manager->delete();
    }
}
