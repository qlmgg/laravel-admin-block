<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;


class Config extends Model
{
    //
    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * @var array
     */
    protected $fillable = [
        'key', 'desc', 'value'
    ];

    /**
     * @param $key
     * @param $data
     * @return mixed
     */
    public static function SetKeyValue($key, $data)
    {
        $data['value'] = serialize($data['value']);
        $conf = Config::where("key", $key)->first();
        if ($conf == null) {
            $foo =  Config::create($data);
        }
        $foo = Config::where("key", $key)->update($data);
        Cache::forget('config_'.$key);
        return $foo;
    }

    /**
     * @param $key
     * @return mixed
     */
    public static function GetKeyValue($key,$field="*")
    {
        $conf = Config::where("key", $key)->first();
        if ($conf == null) {
            return [];
        }
        $conf->value = unserialize($conf->value);
        $configCache =  Cache::rememberForever("config_".$key,function () use (&$conf,&$field){
            return $conf;
        });
        if($field == "*"){
            return $configCache;
        }
        return $configCache->$field;
    }
}
