<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpotifySessionsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('spotify_sessions', function(Blueprint $table) {
            $table->id();

            $table->bigInteger('user_id')
                  ->unsigned()
                  ->index();
            $table->timestamp('timestamp_start')
                  ->index()
                  ->nullable();
            $table->timestamp('timestamp_end')
                  ->index()
                  ->nullable();

            $table->timestamps();

            $table->unique(['user_id', 'timestamp_start']);

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('spotify_sessions');
    }

}
