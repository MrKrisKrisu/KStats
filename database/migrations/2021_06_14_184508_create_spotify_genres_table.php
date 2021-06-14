<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpotifyGenresTable extends Migration {

    public function up() {
        Schema::create('spotify_genres', function(Blueprint $table) {
            $table->id();
            $table->string('seed')->unique();
            $table->string('display_name')->unique()->nullable()->default(null);
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('spotify_genres');
    }
}
