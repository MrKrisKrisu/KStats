<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProfileImageUrlToTwitterProfiles extends Migration {

    public function up(): void {
        Schema::table('twitter_profiles', function(Blueprint $table) {
            $table->string('profile_image_url')->default(null)->nullable()->after('url');
        });
    }

    public function down(): void {
        Schema::table('twitter_profiles', function(Blueprint $table) {
            $table->dropColumn('profile_image_url');
        });
    }
}
