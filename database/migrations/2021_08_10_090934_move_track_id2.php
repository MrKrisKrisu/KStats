<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class MoveTrackId2 extends Migration {

    public function up(): void {
        echo '* Dropping column spotify_play_activities / track_id' . PHP_EOL;
        Schema::table('spotify_play_activities', function(Blueprint $table) {
            $table->dropColumn(['track_id']);
        });

        echo '* Recreating column spotify_play_activities / track_id' . PHP_EOL;
        Schema::table('spotify_play_activities', function(Blueprint $table) {
            $table->unsignedBigInteger('track_id')
                  ->nullable()
                  ->after('timestamp_start');

            $table->foreign('track_id')
                  ->references('id')
                  ->on('spotify_tracks');
        });

        echo '* Copy data for spotify_play_activities / track_id' . PHP_EOL;
        DB::table('spotify_play_activities')->update(['track_id' => DB::raw('trackId')]);
    }

    public function down(): void {
        //
    }
}
