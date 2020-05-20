<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTwitterUnfollowersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('twitter_unfollowers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('account_id')
                ->index()
                ->references('id')->on('twitter_profiles');
            $table->bigInteger('unfollower_id')
                ->index()
                ->references('id')->on('twitter_profiles');
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
        Schema::dropIfExists('twitter_unfollowers');
    }
}
