<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserEmailsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('user_emails', function(Blueprint $table) {
            $table->id();

            $table->string('email')->unique();
            $table->bigInteger('verified_user_id')
                  ->unsigned()
                  ->index()
                  ->nullable();
            $table->bigInteger('unverified_user_id')
                  ->unsigned()
                  ->index()
                  ->nullable();
            $table->string('verification_key')->nullable();

            $table->timestamps();

            $table->foreign('verified_user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            $table->foreign('unverified_user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('user_emails');
    }
}
