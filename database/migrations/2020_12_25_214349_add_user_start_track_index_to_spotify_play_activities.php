<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserStartTrackIndexToSpotifyPlayActivities extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('spotify_play_activities', function(Blueprint $table) {
            $table->dropIndex(['user_id', 'timestamp_start']);
            $table->index(['user_id', 'timestamp_start', 'track_id'], 'user_start_track');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('spotify_play_activities', function(Blueprint $table) {
            $table->dropIndex('user_start_track');
            $table->index(['user_id', 'timestamp_start']);
        });
    }
}
