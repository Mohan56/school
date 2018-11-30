<?php
/**
 * Created by PhpStorm.
 * User: Mohammed
 * Date: 11/26/2018
 * Time: 11:11 AM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class UssdLog extends Model
{
    protected $fillable = ['phone', 'text', 'session_id', 'service_code'];

}
