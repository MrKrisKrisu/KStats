<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTwitterProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('twitter_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('screen_name')->nullable();
            $table->string('location')->nullable();
            $table->string('description')->nullable();
            $table->string('url')->nullable();
            $table->boolean('protected')->nullable();
            $table->integer('followers_count')->nullable();
            $table->integer('friends_count')->nullable();
            $table->integer('listed_count')->nullable();
            $table->integer('statuses_count')->nullable();
            $table->timestamp('account_creation')->nullable();
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
        Schema::dropIfExists('twitter_profiles');
    }
}
