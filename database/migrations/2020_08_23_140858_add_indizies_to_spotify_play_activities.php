<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndiziesToSpotifyPlayActivities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('spotify_play_activities', function (Blueprint $table) {
            $table->index('timestamp_start');
            $table->index('created_at');
            $table->index('device_id');
            $table->index('context');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('spotify_play_activities', function (Blueprint $table) {
            $table->dropIndex('spotify_play_activities_timestamp_start_index');
            $table->dropIndex('spotify_play_activities_created_at_index');
            $table->dropIndex('spotify_play_activities_device_id_index');
            $table->dropIndex('spotify_play_activities_context_index');
        });
    }
}
