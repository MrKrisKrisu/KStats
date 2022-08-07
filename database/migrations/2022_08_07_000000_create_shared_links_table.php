<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void {
        Schema::create('shared_links', static function(Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->uuid('share_id');

            $table->unsignedInteger('spotify_tracks');
            $table->unsignedInteger('spotify_days');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->unique(['user_id', 'share_id']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('shared_links');
    }
};
