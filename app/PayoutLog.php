<?php
/**
 * Created by PhpStorm.
 * User: Mohammed
 * Date: 11/20/2018
 * Time: 10:18 AM
 */

namespace App;


use Illuminate\Database\Eloquent\SoftDeletes;

class PayoutLog
{
    use SoftDeletes;

    protected $fillable = [
        'request_body', 'response_body','deleted_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
}
