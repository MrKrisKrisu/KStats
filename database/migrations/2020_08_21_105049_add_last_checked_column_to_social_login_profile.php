<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLastCheckedColumnToSocialLoginProfile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('social_login_profiles', function (Blueprint $table) {
            $table->timestamp('spotify_lastChecked')->nullable()->after('spotify_lastRefreshed');

            $table->dropIndex('social_login_profiles_user_id_index');
            $table->unique('user_id');
            $table->dropIndex('social_login_profiles_twitter_id_index');
            $table->unique('twitter_id');
            $table->unique('spotify_user_id');
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
            $table->dropColumn('spotify_lastChecked');

            $table->dropUnique('social_login_profiles_user_id_unique');
            $table->dropUnique('social_login_profiles_twitter_id_unique');
            $table->dropUnique('social_login_profiles_spotify_user_id_unique');

            $table->index('twitter_id');
            $table->index('user_id');
        });
    }
}
