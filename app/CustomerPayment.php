<?php
/**
 * Created by PhpStorm.
 * User: Mohammed
 * Date: 11/20/2018
 * Time: 10:17 AM
 */

namespace App;


class CustomerPayment
{
    protected $fillable = [
        'user_id', 'amount', 'reference_no', 'phone_no',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
}
