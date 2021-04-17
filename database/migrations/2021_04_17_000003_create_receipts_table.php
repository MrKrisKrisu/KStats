<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptsTable extends Migration {

    public function up(): void {
        Schema::create('receipts', function(Blueprint $table) {
            $table->id();

            $table->bigInteger('user_id')
                  ->unsigned()
                  ->index()
                  ->nullable();
            $table->bigInteger('shop_id')
                  ->unsigned()
                  ->index();
            $table->timestamp('timestamp')
                  ->index();
            $table->integer('receipt_nr')
                  ->index();
            $table->integer('cashier_nr')->nullable();
            $table->integer('cash_register_nr')->nullable();
            $table->float('amount')
                  ->index();
            $table->integer('earned_loyalty_points');
            $table->binary("raw_receipt")
                  ->nullable();

            $table->timestamps();

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->cascadeOnDelete();

            $table->foreign('shop_id')
                  ->references('id')
                  ->on('shops');
        });
    }

    public function down(): void {
        Schema::dropIfExists('receipts');
    }
}
