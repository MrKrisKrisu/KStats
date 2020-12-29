<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTwitterFollowersTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('twitter_followers', function(Blueprint $table) {
            $table->id();

            $table->bigInteger('follower_id')
                  ->unsigned()
                  ->comment('follows')
                  ->index()
                  ->nullable();
            $table->bigInteger('followed_id')
                  ->unsigned()
                  ->comment('is followed by')
                  ->index()
                  ->nullable();

            $table->timestamps();
            $table->index('updated_at');

            $table->foreign('follower_id')
                  ->references('id')
                  ->on('twitter_profiles')
                  ->onDelete('cascade')
                  ->onUpdate('restrict');

            $table->foreign('followed_id')
                  ->references('id')
                  ->on('twitter_profiles')
                  ->onDelete('cascade')
                  ->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('twitter_followers');
    }
}
