<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexToSPA extends Migration {

    public function up(): void {
        Schema::table('spotify_play_activities', function(Blueprint $table) {
            $table->index(['user_id', 'track_id', 'timestamp_start']);
        });
    }

    public function down(): void {
        Schema::table('spotify_play_activities', function(Blueprint $table) {
            $table->dropIndex(['user_id', 'track_id', 'timestamp_start']);
        });
    }
}
