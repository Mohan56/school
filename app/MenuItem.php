<?php
/**
 * Created by PhpStorm.
 * User: Mohammed
 * Date: 11/20/2018
 * Time: 1:18 PM
 */

namespace App;


class MenuItem
{
    protected $table = 'menu_items';


    protected $fillable = ['menu_id','description','type','next_menu_id','step','confirmation_phrase'];




}
