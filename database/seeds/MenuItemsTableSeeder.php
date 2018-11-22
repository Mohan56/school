<?php

use Illuminate\Database\Seeder;

/**
 * Created by PhpStorm.
 * User: Mohammed
 * Date: 11/19/2018
 * Time: 1:49 PM
 */

class MenuItemsTableSeeder extends Seeder
{
    public function run()
    {
        Eloquent::unguard();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('menu_item')->truncate();

        DB::table('menu_item')->delete();

        DB::table('menu_item')->insert(array(
            array(
                'menu_id' => 1,
                'description' => 'Customer',
                'next_menu_id' => 2,
                'step' => 0,
                'confirmation_phrase' => '',

            ),
            array(
                'menu_id' => 1,
                'description' => 'Artisan',
                'next_menu_id' => 2,
                'step' => 0,
                'confirmation_phrase' => '',

            ),
        ));
    }
}
