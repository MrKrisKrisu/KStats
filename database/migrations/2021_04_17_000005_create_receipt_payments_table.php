<?php

use App\PaymentMethod;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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

        echo "Migrate data to new structure: ". PHP_EOL;
        DB::table('rewe_bons')
          ->orderBy('id')
          ->chunk(25, function($rows) {
              echo ".";
              foreach($rows as $row)
                  DB::table('receipt_payments')->insert([
                                                            'receipt_id'        => $row->id,
                                                            'payment_method_id' => PaymentMethod::where('name', $row->paymentmethod)->first()->id,
                                                            'amount'            => $row->total,
                                                            'created_at'        => $row->created_at,
                                                            'updated_at'        => $row->updated_at
                                                        ]);
          });

        echo PHP_EOL;
    }

    public function down(): void {
        Schema::dropIfExists('receipt_payments');
    }
}
