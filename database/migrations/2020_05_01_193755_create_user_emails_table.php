<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_emails', function (Blueprint $table) {
            $table->increments('id');

            $table->string('email')->unique();
            $table->integer('verified_user_id')
                ->references('id')->on('users')
                ->index()
                ->nullable();


            $table->integer('unverified_user_id')
                ->references('id')->on('users')
                ->index()
                ->nullable();
            $table->string('verification_key')->nullable();

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
        Schema::dropIfExists('user_emails');
    }
}
