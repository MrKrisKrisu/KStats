<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MoveTrackId extends Migration {

    public function up(): void {
        Schema::table('spotify_play_activities', function(Blueprint $table) {
            $table->dropIndex('user_start_track');
            $table->dropForeign(['track_id']);
            $table->dropIndex(['track_id']);
        });
    }

    public function down(): void {
        Schema::table('spotify_play_activities', function(Blueprint $table) {
            $table->index(['user_id', 'timestamp_start', 'track_id'], 'user_start_track');

            $table->foreign('track_id')
                  ->references('track_id')
                  ->on('spotify_tracks');
        });
    }
}
