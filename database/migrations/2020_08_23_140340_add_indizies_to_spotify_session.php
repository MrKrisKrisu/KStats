<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndiziesToSpotifySession extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('spotify_sessions', function (Blueprint $table) {
            $table->index('timestamp_end');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('spotify_sessions', function (Blueprint $table) {
            $table->dropIndex('spotify_sessions_timestamp_end_index');
        });
    }
}
