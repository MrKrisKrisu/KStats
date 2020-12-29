<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpotifyTracksTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('spotify_tracks', function(Blueprint $table) {
            $table->id();

            $table->string('track_id')
                  ->unique();
            $table->string('name');
            $table->string('album_id')
                  ->nullable()
                  ->index();
            $table->boolean('explicit')->nullable();
            $table->tinyInteger('popularity')->unsigned()->nullable();
            $table->tinyInteger('bpm')->unsigned()->nullable();
            $table->string('preview_url')->nullable();
            $table->decimal('danceability', 6, 3)->nullable();
            $table->decimal('energy', 6, 3)->nullable();
            $table->decimal('loudness', 6, 3)->nullable();
            $table->decimal('speechiness', 6, 3)->nullable();
            $table->decimal('acousticness', 6, 3)->nullable();
            $table->decimal('instrumentalness', 6, 3)->nullable();
            $table->smallInteger('key')->nullable();
            $table->smallInteger('mode')->nullable();
            $table->decimal('valence', 4, 3)->nullable();
            $table->integer('duration_ms')->unsigned()->nullable();

            $table->timestamps();

            $table->foreign('album_id')
                  ->references('album_id')
                  ->on('spotify_albums')
                  ->onDelete('restrict')
                  ->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('spotify_tracks');
    }

}
