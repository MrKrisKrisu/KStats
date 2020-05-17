<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpotifyDeviceActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * TODO
         * This table is deprecated... In the main Instance it contains a huge amount of data
         * which needs to migrate to spotify_play_activities
         */
        Schema::create('spotify_device_activities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('device_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('spotify_device_activities');
    }
}
