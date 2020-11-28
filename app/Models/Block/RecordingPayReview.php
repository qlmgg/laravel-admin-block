<?php

namespace App\Models\Block;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RecordingPayReview extends Model
{
    use SoftDeletes;
    //
    protected $table = "block_recording_pay_reviews";

    protected $fillable = [
        'recording_id',
        'from_uid',
        'uid',
        'price',
        'image',
        'status'
    ];
    public  function recording(){
        return $this->belongsTo(Recording::class,"recording_id");
    }
}
