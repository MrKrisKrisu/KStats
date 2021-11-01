<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsForSpotifyInSocialLoginProfiles extends Migration {

    public function up(): void {
        Schema::table('social_login_profiles', function(Blueprint $table) {
            $table->text('spotify_scopes')->default(null)->nullable()->after('spotify_user_id');
            $table->timestamp('spotify_expires_at')->default(null)->nullable()->after('spotify_refreshToken');
        });
    }

    public function down(): void {
        Schema::table('social_login_profiles', function(Blueprint $table) {
            $table->dropColumn(['spotify_scopes', 'spotify_expires_at']);
        });
    }
}
