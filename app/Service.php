<?php
/**
 * Created by PhpStorm.
 * User: Mohammed
 * Date: 11/20/2018
 * Time: 10:17 AM
 */

namespace App;


class Service
{
    protected $table = 'ussd_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'plumber', 'mechanic', 'carpenter', 'painter', 'gardener',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
}
