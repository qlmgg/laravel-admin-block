<?php

namespace App\Models\Block;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class RecordingAppeal extends Model
{
    use SoftDeletes;
    //
    protected $table = "block_recording_appeals";
    public $timestamps = false;
    protected $fillable = [
        'recording_id',
        'complaint_uid',
        'accused_uid',
        'ymd',
        'reason',
        'status',
        'created_at'
    ];
    protected static function boot()
    {
        parent::boot();
        static::creating(function (Model $RecordingAppeal) {
            $RecordingAppeal->ymd = Carbon::now()->format("Ymd");
            $RecordingAppeal->created_at = Carbon::now();
        });
    }
    public  function recording(){
        return $this->belongsTo(Recording::class,"recording_id");
    }
}
