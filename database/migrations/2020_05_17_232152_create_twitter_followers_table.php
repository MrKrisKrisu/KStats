<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTwitterFollowersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('twitter_followers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('follower_id')
                ->comment('follows')
                ->index()
                ->references('twitter_id')->on('twitter_profiles');
            $table->bigInteger('followed_id')
                ->comment('is followed by')
                ->index()
                ->references('twitter_id')->on('twitter_profiles');
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
        Schema::dropIfExists('twitter_followers');
    }
}
