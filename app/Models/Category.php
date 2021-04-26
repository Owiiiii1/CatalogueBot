<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Category extends Model
{
    use AsSource;

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @param $name
     * @param $description
     * @return \Illuminate\Database\Eloquent\Builder|Model
     */
    public static function CreateNew($name, $description, $image)
    {
        $ctg = Category::query()->firstOrCreate([
            'name' => $name,
            'description' => $description,
            'image' => $image,
        ]);

        return $ctg;
    }

    /**
     * @param $id
     */
    public static function DeleteCat($id)
    {
        Category::query()->where('id', '=', $id)->delete();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function brands()
    {
        return $this->belongsToMany('App\Models\Brand', 'brands_categories');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function certificates()
    {
        return $this->hasMany('App\Models\Certificate');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stocks()
    {
        return $this->hasMany('App\Models\Stocks');
    }

    public function prices()
    {
        return $this->hasMany('App\Models\Price');
    }
}
