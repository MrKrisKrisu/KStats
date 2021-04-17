<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptPositionsTable extends Migration {

    public function up(): void {
        Schema::create('receipt_positions', function(Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('receipt_id')
                  ->index();
            $table->unsignedBigInteger('product_id')
                  ->index();
            $table->integer('amount')->nullable();
            $table->float('weight', 8, 3)->nullable();
            $table->float('single_price');

            $table->timestamps();

            $table->foreign('receipt_id')
                  ->references('id')
                  ->on('receipts')
                  ->cascadeOnDelete();
            $table->foreign('product_id')
                  ->references('id')
                  ->on('products');
        });
    }

    public function down(): void {
        Schema::dropIfExists('receipt_positions');
    }
}
