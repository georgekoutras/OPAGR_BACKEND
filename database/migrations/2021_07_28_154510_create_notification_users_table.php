<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification_users', function (Blueprint $table) {
            /* --------------------------------------------------- */
            $table->foreignId('notification_id')->constrained();
            $table->foreignId('cultivation_id')->constrained();
            $table->foreignId('user_id')->constrained();
            /* --------------------------------------------------- */
            $table->primary(['notification_id','cultivation_id','user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('notification_users');
    }
}
