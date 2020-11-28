<?php

namespace App\Models\Block;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LotteryLog extends Model
{
    use SoftDeletes;
    //
    protected $table="block_lottery_logs";

    public function user()
    {
        return $this->belongsTo(User::class, "uid");
    }
}
