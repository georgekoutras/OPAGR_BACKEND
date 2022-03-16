<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            /* ---------------------------------------------------------- */
            $table->enum('type', ['functional','threshold']);
            $table->foreignId('device_id')->constrained();
            $table->text('message');
            /* ---------------------------------------------------------- */
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::table('notifications', function (Blueprint $table){
           $table->dropSoftDeletes();
        });
    }
}
