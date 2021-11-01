<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveTempColumnFromSpotifyPlayActivities extends Migration {

    public function up(): void {
        Schema::table('spotify_play_activities', function(Blueprint $table) {
            $table->dropForeign('spotify_play_activities_trackid_foreign');
            $table->dropColumn(['trackId']);
        });
    }

}
