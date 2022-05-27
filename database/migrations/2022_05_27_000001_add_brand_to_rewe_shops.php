<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void {
        Schema::table('rewe_shops', static function(Blueprint $table) {
            $table->unsignedBigInteger('brand_id')->nullable()->after('name');
            $table->foreign('brand_id')->references('id')->on('brands');
        });

        DB::table('rewe_shops')->update([
                                            'brand_id' => 1,
                                        ]);
    }

    public function down(): void {
        Schema::table('rewe_shops', static function(Blueprint $table) {
            $table->dropForeign(['brand_id']);
            $table->dropColumn(['brand_id']);
        });
    }
};
