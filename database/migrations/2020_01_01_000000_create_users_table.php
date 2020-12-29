<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('users', function(Blueprint $table) {
            $table->id();

            $table->string('username')
                  ->unique();
            $table->string('email')
                  ->unique()
                  ->nullable(); //TODO: remove this und just use user_emails table
            $table->timestamp('email_verified_at')
                  ->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamp('last_login')
                  ->useCurrent();
            $table->string('locale', 5)
                  ->nullable()
                  ->default(NULL);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('users');
    }

}
