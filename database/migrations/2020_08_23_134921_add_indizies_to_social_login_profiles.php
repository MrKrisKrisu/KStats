<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndiziesToSocialLoginProfiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('social_login_profiles', function (Blueprint $table) {
            $table->index('spotify_lastRefreshed');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('social_login_profiles', function (Blueprint $table) {
            $table->dropIndex('social_login_profiles_spotify_lastRefreshed_index');
        });
    }
}
