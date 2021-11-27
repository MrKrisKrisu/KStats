<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSpotifyLastFetchedToSocialLoginProfile extends Migration {

    public function up(): void {
        Schema::table('social_login_profiles', function(Blueprint $table) {
            $table->timestamp('spotify_last_fetched')->nullable()->after('spotify_lastRefreshed');
        });
    }

    public function down(): void {
        Schema::table('social_login_profiles', function(Blueprint $table) {
            $table->dropColumn(['spotify_last_fetched']);
        });
    }
}
