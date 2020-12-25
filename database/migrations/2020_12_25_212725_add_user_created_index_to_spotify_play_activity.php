<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserCreatedIndexToSpotifyPlayActivity extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('spotify_play_activities', function(Blueprint $table) {
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('spotify_play_activities', function(Blueprint $table) {
            $table->dropIndex(['user_id', 'created_at']);
        });
    }
}
