<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndiziesToTwitterApiRequest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('twitter_api_requests', function (Blueprint $table) {
            $table->index('endpoint');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('twitter_api_requests', function (Blueprint $table) {
            $table->dropIndex('twitter_api_requests_endpoint_index');
            $table->dropIndex('twitter_api_requests_created_at_index');
        });
    }
}
