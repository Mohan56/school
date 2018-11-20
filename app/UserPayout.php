<?php
/**
 * Created by PhpStorm.
 * User: Mohammed
 * Date: 11/20/2018
 * Time: 11:03 AM
 */

namespace App;


use Illuminate\Database\Eloquent\SoftDeletes;

class UserPayout
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'amount','deleted_at'
        ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
}
