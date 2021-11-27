<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddLastCheckedToTwitterFollower extends Migration {

    public function up(): void {
        Schema::table('twitter_followers', function(Blueprint $table) {
            $table->timestamp('last_checked')->nullable()->after('followed_id');
        });
        DB::table('twitter_followers')->update([
                                                   'last_checked' => DB::raw('updated_at')
                                               ]);
    }

    public function down(): void {
        Schema::table('twitter_followers', function(Blueprint $table) {
            $table->dropColumn(['last_checked']);
        });
    }
}
