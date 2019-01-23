<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SpotComment extends Model
{
    //
    protected $table = 'spot_comments';

    protected $dates = [
        'created_at',
        'updated_at'
    ];
}
