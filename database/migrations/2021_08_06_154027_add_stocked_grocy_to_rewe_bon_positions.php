<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStockedGrocyToReweBonPositions extends Migration {

    public function up(): void {
        Schema::table('rewe_bon_positions', function(Blueprint $table) {
            $table->string('grocy_transaction_id')
                  ->nullable()
                  ->default(null)
                  ->after('single_price');
        });
    }

    public function down(): void {
        Schema::table('rewe_bon_positions', function(Blueprint $table) {
            $table->dropColumn(['grocy_transaction_id']);
        });
    }
}
