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
     * @todo Why not just put this in the users table? Would be easier to handle.
     */
    public function up()
    {
        Schema::create('social_login_profiles', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('user_id')
                ->unsigned()
                ->unique();

            $table->bigInteger('twitter_id')
                ->unique()
                ->unsigned()
                ->nullable();
            $table->string('twitter_token')
                ->nullable();
            $table->string('twitter_tokenSecret')
                ->nullable();

            $table->string('spotify_user_id')
                ->unique()
                ->nullable();
            $table->string('spotify_accessToken')
                ->nullable();
            $table->string('spotify_refreshToken')
                ->nullable();
            $table->timestamp('spotify_lastRefreshed')
                ->index()
                ->nullable();

            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('twitter_id')
                ->references('id')
                ->on('twitter_profiles')
                ->onDelete('restrict')
                ->onUpdate('restrict');
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
