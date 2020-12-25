<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserStartIndexToSpotifyPlayActivity extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('spotify_play_activity', function(Blueprint $table) {
            $table->index(['user_id', 'timestamp_start']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('spotify_play_activity', function(Blueprint $table) {
            $table->dropIndex(['user_id', 'timestamp_start']);
        });
    }
}
