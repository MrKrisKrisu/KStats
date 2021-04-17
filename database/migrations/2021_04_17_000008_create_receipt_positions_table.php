<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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

        echo "Migrate data to new structure: " . PHP_EOL;
        DB::table('rewe_bon_positions')
          ->orderBy('id')
          ->chunk(250, function($rows) {
              echo ".";
              foreach($rows as $row)
                  DB::table('receipt_positions')->insert([
                                                             'id'           => $row->id,
                                                             'receipt_id'   => $row->bon_id,
                                                             'product_id'   => $row->product_id,
                                                             'amount'       => $row->amount,
                                                             'weight'       => $row->weight,
                                                             'single_price' => $row->single_price,
                                                             'created_at'   => $row->created_at,
                                                             'updated_at'   => $row->updated_at
                                                         ]);
          });
        echo PHP_EOL;
    }

    public function down(): void {
        Schema::dropIfExists('receipt_positions');
    }
}
