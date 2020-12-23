<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyToSpotifyPlayActivities extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('spotify_play_activities', function(Blueprint $table) {
            $table->foreign('context_id')
                  ->references('id')
                  ->on('spotify_contexts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('spotify_play_activities', function(Blueprint $table) {
            $table->dropForeign(['context_id']);
        });
    }
}
