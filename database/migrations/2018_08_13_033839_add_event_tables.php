<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEventTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_events', function (Blueprint $table) {
            $table->increments('master_event_id');
            $table->string('type_event_name');
            $table->timestamps();
        });

        Schema::create('events', function (Blueprint $table) {
            $table->increments('event_id');
            $table->string('event_name');
            $table->string('event_place');
            $table->dateTime('event_start');
            $table->dateTime('event_finish');
            $table->integer('event_type_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->timestamps();

            $table->foreign('event_type_id')
                ->references('master_event_id')
                ->on('master_events')
                ->onDelete('cascade');
        
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');                  
        });        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
        Schema::dropIfExists('master_events');
    }
}
