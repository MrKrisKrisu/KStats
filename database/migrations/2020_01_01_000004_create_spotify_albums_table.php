<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpotifyAlbumsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('spotify_albums', function(Blueprint $table) {
            $table->id();

            $table->string('album_id')->unique(); //TODO: Make this to primary key
            $table->string('name');
            $table->string('imageUrl')->nullable();
            $table->date('release_date')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('spotify_albums');
    }

}
