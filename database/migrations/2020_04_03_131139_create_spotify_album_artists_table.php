<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpotifyAlbumArtistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spotify_album_artists', function (Blueprint $table) {
            $table->string('album_id')
                ->references('album_id')->on('spotify_albums');
            $table->string('artist_id')
                ->references('artist_id')->on('spotify_artists');

            $table->unique(array('album_id', 'artist_id'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('spotify_album_artists');
    }
}
