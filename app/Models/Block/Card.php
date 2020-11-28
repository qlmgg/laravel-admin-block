<?php

namespace App\Models\Block;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Card extends Model
{
    use SoftDeletes;
    //
    protected $table="block_cards";

}
