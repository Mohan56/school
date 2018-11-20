<?php
/**
 * Created by PhpStorm.
 * User: Mohammed
 * Date: 11/20/2018
 * Time: 1:17 PM
 */

namespace App;


class Menu
{
    protected $table = 'menus';


    protected $fillable = ['title','type','is_parent','confirmation_message'];
}
