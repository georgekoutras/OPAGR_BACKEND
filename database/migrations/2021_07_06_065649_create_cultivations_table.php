<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCultivationsTable extends Migration
{

    public function up()
    {
        Schema::create('cultivations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('comments');
            $table->json('location');
            /* --------------------------------------------------- */
            $table->foreignId('user_id')->constrained();
            $table->foreignId('cultivation_type_id')->constrained();
            /* --------------------------------------------------- */
            $table->char('notification_index','26')->default('00000000000000000000000000');
            /* --------------------------------------------------- */
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::table('cultivations', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
