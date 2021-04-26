<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBotUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bot_users', function (Blueprint $table) {
            $table->id();
            $table->string('source', 20)->default('start');
            $table->string('msng_driver')->default('Telegram');
            $table->string('msng_id');
            $table->string('msng_username')->default('Anonymous');
            $table->string('msng_firstname')->nullable();
            $table->string('phone')->nullable();
            $table->string('name')->nullable();
            $table->unsignedInteger('region_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bot_users');
    }
}
