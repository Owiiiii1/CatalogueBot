<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Region extends Model
{
    use AsSource;

    public $timestamps = false;

    /**
     * @var array
     */
    protected $guarded = [];

    public static function CreateNew($name, $description, $manager)
    {
        $collection = Region::query()->firstOrCreate([
            'name' => $name,
            'description' => $description,
            'manager_id' => $manager,

        ]);

        return $collection;
    }

    /**
     * @param $id
     * @throws \Exception
     */
    public static function DeleteRegion($id)
    {
        $collection = Region::query()->where('id', '=', $id)->first();
        $collection->delete();
    }

    public function prices()
    {
        return $this->hasMany('App\Models\Price');
    }
}
