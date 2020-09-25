<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpotifyPlayActivitiesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spotify_play_activities', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('user_id')
                ->unsigned()
                ->index();
            $table->timestamp('timestamp_start')
                ->index();
            $table->string('track_id')
                ->index(); //TODO: use internal track id
            $table->integer('progress_ms');
            $table->string('context')
                ->index()
                ->nullable();
            $table->string('context_uri')
                ->nullable();
            $table->bigInteger('device_id')
                ->index()
                ->unsigned()
                ->nullable();

            $table->timestamps();
            $table->index('created_at');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('device_id')
                ->references('id')
                ->on('spotify_devices')
                ->onDelete('set null')
                ->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('spotify_play_activities');
    }

}
