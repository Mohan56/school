<?php
/**
 * Created by PhpStorm.
 * User: Mohammed
 * Date: 1/9/2019
 * Time: 11:17 AM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class UssdResponse extends Model
{
    protected $table = 'ussd_response';

    protected $fillable = ['user_id','menu_id','response','menu_item_id'];
}
