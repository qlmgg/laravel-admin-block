<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use function foo\func;

class UserSign extends Model
{


    public $timestamps = false;
    /**
     * @var array
     */
    protected $fillable = [
        'uid','ymd','reward_currency','reward_amount','source','status','created_at'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Model $model) {
            $model->ymd = Carbon::now()->format("Ymd");
            $model->created_at = Carbon::now();
        });
    }
}
