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
            $table->bigInteger('album_id')->unsigned();
            $table->bigInteger('artist_id')->unsigned();

            $table->unique(['album_id', 'artist_id']);

            $table->foreign('album_id')
                ->references('id')
                ->on('spotify_albums')
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
        Schema::dropIfExists('spotify_album_artists');
    }
}
