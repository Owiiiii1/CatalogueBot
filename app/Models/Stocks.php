<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Screen\AsSource;

class Stocks extends Model
{
    use AsSource, Attachable;

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @param $name
     * @param $catID
     * @return \Illuminate\Database\Eloquent\Builder|Model
     */
    public static function CreateNew($name, $catID)
    {
        $stocks = Stocks::query()->firstOrCreate([
            'name' => $name,
            'cat_id' => $catID

        ]);

        return $stocks;
    }

    /**
     * @param $id
     * @throws \Exception
     */
    public static function DeleteStock($id)
    {
        $stocks = Stocks::query()->where('id', '=', $id)->first();
        $stocks->delete();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }
}
