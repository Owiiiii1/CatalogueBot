<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Screen\AsSource;

class Price extends Model
{
    use AsSource, Attachable;

    public $timestamps = false;

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @param $name
     * @param $catID
     * @param $regionID
     * @return Price
     */
    public static function CreateNew($name, $catID)
    {
        $price = Price::firstOrCreate([
            'name' => $name,
            'cat_id' => $catID,
        ]);

        return $price;
    }

    /**
     * @param $id
     * @throws \Exception
     */
    public static function DeletePrice($id)
    {
        $price = Price::query()->where('id', '=', $id)->first();
        $price->delete();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function location()
    {
        return $this->belongsTo('App\Models\Region');
    }
}
