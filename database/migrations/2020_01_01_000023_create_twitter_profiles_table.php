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
            $table->string('screen_name')->index()->nullable();
            $table->string('location')->index()->nullable();
            $table->string('description')->nullable();
            $table->string('url')->nullable();
            $table->boolean('protected')->nullable();
            $table->integer('followers_count')->index()->unsigned()->nullable();
            $table->integer('friends_count')->index()->unsigned()->nullable();
            $table->integer('listed_count')->unsigned()->nullable();
            $table->integer('statuses_count')->unsigned()->nullable();
            $table->timestamp('account_creation')->index()->nullable();

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
