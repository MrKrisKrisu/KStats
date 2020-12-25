<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSpotifyIndexToSocialLoginProfiles extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('social_login_profiles', function(Blueprint $table) {
            $table->index(['spotify_accessToken', 'spotify_lastRefreshed'], 'spotify_req');
            $table->index(['twitter_token']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('social_login_profiles', function(Blueprint $table) {
            $table->dropIndex('spotify_req');
            $table->dropIndex(['twitter_token']);
        });
    }
}
