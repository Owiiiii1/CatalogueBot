<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Screen\AsSource;

class Brand extends Model
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
     * @return \Illuminate\Database\Eloquent\Builder|Model
     */
    public static function CreateNew($name, $description, $image)
    {
        $brand = Brand::query()->firstOrCreate([
            'name' => $name,
            'description' => $description,
            'image' => $image,
        ]);

        return $brand;
    }

    /**
     * @param $id
     */
    public static function DeleteBrand($id)
    {
        $brand = Brand::query()->where('id', '=', $id)->first();
        $brand->categories()->detach();
        $brand->delete();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany('App\Models\Category', 'brands_categories');
    }

    public function collections()
    {
        return $this->hasMany('App\Models\Collection');
    }
}
