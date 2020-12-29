<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpotifyTrackRatingsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('spotify_track_ratings', function(Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')
                  ->index();
            $table->unsignedBigInteger('track_id')
                  ->index();
            $table->boolean('rating')
                  ->index()
                  ->comment('liked=1, disliked=0, skipped=-1');
            $table->timestamps();

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->cascadeOnDelete()
                  ->cascadeOnUpdate();

            $table->foreign('track_id')
                  ->references('id')
                  ->on('spotify_tracks')
                  ->cascadeOnDelete()
                  ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('spotify_track_ratings');
    }
}
