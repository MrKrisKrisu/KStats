<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUniqueKeyToRatings extends Migration {

    public function up(): void {
        Schema::table('spotify_track_ratings', function(Blueprint $table) {
            $table->unique(['user_id', 'track_id']);
        });
    }

    public function down(): void {
        Schema::table('spotify_track_ratings', function(Blueprint $table) {
            $table->dropUnique(['user_id', 'track_id']);
        });
    }
}
