<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaymentTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_sessions', function (Blueprint $table) {
            $table->increments('payment_session_id');
            $table->date('payment_start_date');
            $table->date('payment_finish_date');
            $table->timestamps();
        });

        Schema::create('payment_types', function (Blueprint $table) {
            $table->increments('payment_type_id');
            $table->string('payment_name');
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->date('payment_submitted');
            $table->date('payment_verified');
            $table->integer('payment_verifier')->unsigned();
            $table->integer('payment_session_id')->unsigned();
            $table->integer('payment_type_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->timestamps();

            $table->foreign('payment_session_id')
                ->references('payment_session_id')
                ->on('payment_sessions')
                ->onDelete('cascade');
            
            $table->foreign('payment_type_id')
                ->references('payment_type_id')
                ->on('payment_types')
                ->onDelete('cascade');    
        
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade'); 

            $table->primary(['user_id', 'payment_session_id', 'payment_type_id']);    
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('payment_sessions');
        Schema::dropIfExists('payment_types');
    }
}
