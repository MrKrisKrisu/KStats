<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDurationToSpotifyPlayActivities extends Migration {

    public function up(): void {
        Schema::table('spotify_play_activities', function(Blueprint $table) {
            $table->unsignedBigInteger('trackId')
                  ->comment('new temporary')
                  ->nullable()
                  ->default(null)
                  ->after('track_id');

            $table->foreign('trackId')
                  ->references('id')
                  ->on('spotify_tracks');
        });
    }

    public function down(): void {
        Schema::table('spotify_play_activities', function(Blueprint $table) {
            $table->dropColumn(['trackId']);
        });
    }
}
