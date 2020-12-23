<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyTrackIdToSpotifyPlayActivities extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('spotify_play_activities', function(Blueprint $table) {
            $table->foreign('track_id')
                  ->references('track_id')
                  ->on('spotify_tracks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('spotify_play_activities', function(Blueprint $table) {
            $table->dropForeign(['track_id']);
        });
    }
}
