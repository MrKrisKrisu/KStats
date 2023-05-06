<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('social_login_profiles', static function(Blueprint $table) {
            $table->dropForeign('social_login_profiles_twitter_id_foreign');
            $table->dropColumn(['twitter_id', 'twitter_token', 'twitter_tokenSecret']);
        });

        Schema::drop('twitter_api_requests');
        Schema::drop('twitter_dailies');
        Schema::drop('twitter_unfollowers');
        Schema::drop('twitter_followers');
        Schema::drop('twitter_profiles');
    }
};
