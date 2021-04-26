<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class BotUser extends Model
{
    use HasFactory, AsSource;

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var string
     */
    protected $table = 'bot_users';

    /**
     * @param $msng_driver
     * @param $msng_id
     * @param $msng_username
     * @param $msng_firstname
     * @param string $source
     * @return \Illuminate\Database\Eloquent\Builder|Model
     */
    public static function CreateNew($msng_driver, $msng_id, $msng_username, $msng_firstname, $source = 'start')
    {
        $botuser = BotUser::query()->firstOrCreate([
            'source' => ($source > 0) ? $source : 'start',
            'msng_driver' => $msng_driver,
            'msng_id' => $msng_id,
            'msng_username' => isset($msng_username) ? $msng_username : 'Anonymous',
            'msng_firstname' => isset($msng_firstname) ? $msng_firstname : 'noname',

        ]);

        return $botuser;
    }

    /**
     * @param $msng_id
     * @return mixed
     */
    public static function GetByMessengerId($msng_id)
    {
        $user = BotUser::where('msng_id', '=', $msng_id)->first();
        return $user;
    }

    /**
     * @return mixed
     */
    public function MessengerId()
    {
        return $this->msng_id;
    }

}
