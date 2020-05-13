<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpotifyTrackArtistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spotify_track_artists', function (Blueprint $table) {
            $table->integer('track_id')
                ->references('id')->on('spotify_tracks');
            $table->integer('artist_id')
                ->references('id')->on('spotify_artists');

            $table->unique(array('track_id', 'artist_id'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('spotify_track_artists');
    }
}
