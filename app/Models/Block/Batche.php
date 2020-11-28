<?php

namespace App\Models\Block;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Batche extends Model
{
    use SoftDeletes;
    //
    protected $table = "block_batches";

    protected $fillable = [
        "cycle",
        "status",
        'queue',
    ];

    public static function installOneId($cycle){
        $batch = Batche::where(['cycle' => $cycle])->first();
        if ($batch == null) {
            $batch = Batche::create(['cycle' => $cycle]);
        }
        return $batch->id;
    }

    public function recording()
    {
        return $this->hasMany(Recording::class, "batch_id");
    }
}
