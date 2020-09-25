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
                ->unsigned()
                ->index();
            $table->bigInteger('unfollower_id')
                ->nullable()
                ->unsigned()
                ->index();
            $table->timestamp('unfollowed_at')->nullable();

            $table->timestamps();

            $table->foreign('account_id')
                ->references('id')
                ->on('twitter_profiles')
                ->onDelete('cascade')
                ->onUpdate('restrict');

            $table->foreign('unfollower_id')
                ->references('id')
                ->on('twitter_profiles')
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
        Schema::dropIfExists('twitter_unfollowers');
    }
}
