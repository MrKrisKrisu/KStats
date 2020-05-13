<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpotifyPlayActivitiesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spotify_play_activities', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')
                ->references('id')->on('users')
                ->index();
            $table->timestamp('timestamp_start');
            $table->string('track_id')
                ->references('id')->on('spotify_tracks')
                ->index();
            $table->integer('progress_ms');
            $table->string('context')->nullable();
            $table->string('context_uri')->nullable();
            $table->integer('device_id')->nullable(); //TODO: Data is currently in table spotify_deviceactivities -> migrate
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
        Schema::dropIfExists('spotify_play_activities');
    }

}
