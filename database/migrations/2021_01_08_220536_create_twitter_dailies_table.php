<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTwitterDailiesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void {
        Schema::create('twitter_dailies', function(Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('profile_id');
            $table->date('date');
            $table->unsignedInteger('follower_count');
            $table->unsignedInteger('friends_count');
            $table->unsignedInteger('statuses_count');
            $table->timestamps();

            $table->unique(['profile_id', 'date']);

            $table->foreign('profile_id')
                  ->references('id')
                  ->on('twitter_profiles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void {
        Schema::dropIfExists('twitter_dailies');
    }
}
