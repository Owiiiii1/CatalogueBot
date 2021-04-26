<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Screen\AsSource;

class Certificate extends Model
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
     * @param $categoryID
     * @return \Illuminate\Database\Eloquent\Builder|Model
     */
    public static function CreateNew($name, $description, $categoryID)
    {
        $certificate = Certificate::query()->firstOrCreate([
            'name' => $name,
            'description' => $description,
            'category_id' => $categoryID

        ]);

        return $certificate;
    }

    /**
     * @param $id
     * @throws \Exception
     */
    public static function DeleteCertificate($id)
    {
        $certificate = Certificate::query()->where('id', '=', $id)->first();
        $certificate->delete();
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }
}
