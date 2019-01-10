<?php

use Illuminate\Database\Seeder;

/**
 * Created by PhpStorm.
 * User: Mohammed
 * Date: 11/19/2018
 * Time: 1:50 PM
 */

class MenusTableSeeder extends Seeder
{
    public function run()
    {
        //menu types type 0 - authentication mini app, Type 1 - another menu mini app, type 2 leads to a process app, 3 gives information directly
        Eloquent::unguard();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('menu')->truncate();

        DB::table('menu')->delete();

        DB::table('menu')->insert(array(
            array(
                'title' => 'Welcome to Jua Kali Artisans',
                'is_parent' => 1,
                'type' => 1,
                'confirmation_message' => "",
            ),
            array(
                'title' => 'Registration',
                'is_parent' => 0,
                'type' => 2,
                'confirmation_message' => "",
            ),
        ));
    }
}
