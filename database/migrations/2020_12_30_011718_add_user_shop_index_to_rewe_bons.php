<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserShopIndexToReweBons extends Migration {

    public function up(): void {
        Schema::table('rewe_bons', function(Blueprint $table) {
            $table->index(['user_id', 'shop_id']);
        });
    }


    public function down(): void {
        Schema::table('rewe_bons', function(Blueprint $table) {
            $table->dropIndex(['user_id', 'shop_id']);
        });
    }
}
