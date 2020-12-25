<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBpmIndexToSpotifyTracks extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('spotify_tracks', function(Blueprint $table) {
            $table->index(['bpm']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('spotify_tracks', function(Blueprint $table) {
            $table->dropIndex(['bpm']);
        });
    }
}
