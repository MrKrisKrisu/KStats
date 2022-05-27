<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void {
        Schema::table('rewe_shops', static function(Blueprint $table) {
            $table->unsignedBigInteger('local_shop_id')->nullable()->after('brand_id');
        });

        DB::table('rewe_shops')->update([
                                            'local_shop_id' => DB::raw('id'),
                                        ]);

        Schema::table('rewe_shops', static function(Blueprint $table) {
            $table->unique(['brand_id', 'local_shop_id']);
        });
    }

    public function down(): void {
        Schema::table('rewe_shops', static function(Blueprint $table) {
            $table->dropUnique(['brand_id', 'local_shop_id']);
            $table->dropColumn(['local_shop_id']);
        });
    }
};
