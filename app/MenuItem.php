<?php
/**
 * Created by PhpStorm.
 * User: Mohammed
 * Date: 11/20/2018
 * Time: 1:18 PM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $table = 'menu_item';


    protected $fillable = ['menu_id','description','type','next_menu_id','step','confirmation_phrase'];




}
