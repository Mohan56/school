<?php
/**
 * Created by PhpStorm.
 * User: Mohammed
 * Date: 11/20/2018
 * Time: 10:19 AM
 */

namespace App;


use Illuminate\Database\Eloquent\SoftDeletes;

class MpesaTransaction
{
    use SoftDeletes;

    protected $fillable = [
        'phone', 'transaction_id', 'Amount', 'transaction_time','deleted_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
}
