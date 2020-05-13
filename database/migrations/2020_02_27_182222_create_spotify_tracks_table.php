<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpotifyTracksTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spotify_tracks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('track_id')->unique();
            $table->string('name');
            $table->string('album_id')->nullable()
                ->references('id')->on('spotify_albums')
                ->index();
            $table->boolean('explicit')->nullable();
            $table->integer('popularity')->nullable();
            $table->integer('bpm')->nullable();
            $table->string('preview_url')->nullable();
            $table->float('danceability', 22, 3)->nullable();
            $table->float('energy', 22, 3)->nullable();
            $table->float('loudness', 22, 3)->nullable();
            $table->float('speechiness', 22, 3)->nullable();
            $table->float('acousticness', 22, 3)->nullable();
            $table->float('instrumentalness', 22, 3)->nullable();
            $table->smallInteger('key')->nullable();
            $table->smallInteger('mode')->nullable();
            $table->float('valence', 22, 3)->nullable();
            $table->integer('duration_ms')->nullable();
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
        Schema::dropIfExists('spotify_tracks');
    }

}
