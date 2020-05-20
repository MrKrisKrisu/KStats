<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSocialLoginProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('social_login_profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->index()
                ->references('id')->on('users');

            $table->bigInteger('twitter_id')
                ->index()
                ->references('id')->on('twitter_profiles')
                ->nullable();
            $table->string('twitter_token')->nullable();
            $table->string('twitter_tokenSecret')->nullable();
            $table->string('spotify_user_id')->nullable();
            $table->string('spotify_accessToken')->nullable();
            $table->string('spotify_refreshToken')->nullable();
            $table->timestamp('spotify_lastRefreshed')->nullable();
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
        Schema::dropIfExists('social_login_profiles');
    }
}
