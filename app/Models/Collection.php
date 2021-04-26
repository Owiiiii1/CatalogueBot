<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Screen\AsSource;

class Collection extends Model
{
    use AsSource, Attachable;

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @param $name
     * @param $description
     * @param $image
     * @param $brandID
     * @return \Illuminate\Database\Eloquent\Builder|Model
     */

    public static function CreateNew($name, $description, $image, $brandID)
    {
        $collection = Collection::query()->firstOrCreate([
            'name' => $name,
            'description' => $description,
            'image' => $image,
            'brand_id' => $brandID

        ]);

        return $collection;
    }

    /**
     * @param $id
     * @throws \Exception
     */
    public static function DeleteCol($id)
    {
        $collection = Collection::query()->where('id', '=', $id)->first();
        $collection->delete();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function brand()
    {
        return $this->belongsTo('App\Models\Brand');
    }
}
