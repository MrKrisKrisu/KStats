<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptPaymentsTable extends Migration {

    public function up(): void {
        Schema::create('receipt_payments', function(Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('receipt_id');
            $table->unsignedBigInteger('payment_method_id');
            $table->decimal('amount');

            $table->timestamps();

            $table->foreign('receipt_id')
                  ->references('id')
                  ->on('receipts')
                  ->cascadeOnDelete();
            $table->foreign('payment_method_id')
                  ->references('id')
                  ->on('payment_methods');
        });
    }

    public function down(): void {
        Schema::dropIfExists('receipt_payments');
    }
}
