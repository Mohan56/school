<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('session')->default(0);
            $table->integer('progress')->default(0);
            $table->integer('menu_id')->default(0);
            $table->integer('confirm_from')->default(0);
            $table->integer('menu_item_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('session');
            $table->dropColumn('progress');
            $table->dropColumn('menu_id');
            $table->dropColumn('confirm_from');
            $table->dropColumn('menu_item_id');
        });
    }
}
