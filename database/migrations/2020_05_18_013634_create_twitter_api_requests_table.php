<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTwitterApiRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('twitter_api_requests', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('twitter_profile_id')
                ->index()
                ->references('id')->on('twitter_profiles')
                ->comment('NULL=Application Token used')
                ->nullable();
            $table->string('endpoint');
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
        Schema::dropIfExists('twitter_api_requests');
    }
}
