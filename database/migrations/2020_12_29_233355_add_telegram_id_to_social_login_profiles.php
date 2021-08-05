<?php

use App\Models\UserSettings;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTelegramIdToSocialLoginProfiles extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('social_login_profiles', function(Blueprint $table) {
            $table->string('telegram_id')
                  ->nullable()
                  ->default(null)
                  ->after('user_id');
        });

        $oldQ = UserSettings::where('name', 'telegram_id')
                            ->orWhere('name', 'telegramId')
                            ->get();

        foreach($oldQ as $old) {
            echo "Migrate Telegram User " . $old->user->username . "...\r\n";
            $old->user->socialProfile->update([
                                                  'telegram_id' => $old->val
                                              ]);
            $old->delete();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('social_login_profiles', function(Blueprint $table) {
            $table->dropColumn('telegram_id');
        });
    }
}
