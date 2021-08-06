<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGrocyToSocialLoginProfile extends Migration {

    public function up(): void {
        Schema::table('social_login_profiles', function(Blueprint $table) {
            $table->string('grocy_key')->nullable()->after('spotify_lastRefreshed');
            $table->string('grocy_host')->nullable()->after('spotify_lastRefreshed');
        });
    }

    public function down(): void {
        Schema::table('social_login_profiles', function(Blueprint $table) {
            $table->dropColumn(['grocy_host', 'grocy_key']);
        });
    }
}
