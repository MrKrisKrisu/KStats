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
            $table->bigInteger('track_id')
                ->unsigned();
            $table->bigInteger('artist_id')
                ->unsigned();

            $table->unique(['track_id', 'artist_id']);

            $table->foreign('track_id')
                ->references('id')
                ->on('spotify_tracks')
                ->onDelete('restrict')
                ->onUpdate('restrict');

            $table->foreign('artist_id')
                ->references('id')
                ->on('spotify_artists')
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
        Schema::dropIfExists('spotify_track_artists');
    }
}
