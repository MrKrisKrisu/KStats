<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDurationToSpotifyPlayActivities2 extends Migration {

    public function up(): void {
        Schema::table('spotify_play_activities', function(Blueprint $table) {
            $table->unsignedInteger('duration')
                  ->default(60)
                  ->comment('seconds')
                  ->after('track_id');
        });
    }

    public function down(): void {
        Schema::table('spotify_play_activities', function(Blueprint $table) {
            $table->dropColumn(['duration']);
        });
    }
}
