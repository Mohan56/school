<?php
/**
 * Created by PhpStorm.
 * User: Mohammed
 * Date: 11/20/2018
 * Time: 10:02 AM
 */

namespace App;


class Job
{
    /**
     * The attribute
     * es that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'location', 'service', 'budget', 'actual_Payment',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
}
