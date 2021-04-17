<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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

        echo "Migrate data to new structure: ". PHP_EOL;
        DB::table('rewe_bons')
          ->orderBy('id')
          ->chunk(25, function($rows) {
              echo ".";
              foreach($rows as $row)
                  DB::table('receipts')->insert([
                                                    'id'                    => $row->id,
                                                    'user_id'               => $row->user_id,
                                                    'shop_id'               => $row->shop_id,
                                                    'timestamp'             => $row->timestamp_bon,
                                                    'receipt_nr'            => $row->bon_nr,
                                                    'cashier_nr'            => $row->cashier_nr,
                                                    'cash_register_nr'      => $row->cashregister_nr,
                                                    'amount'                => $row->total,
                                                    'earned_loyalty_points' => $row->earned_payback_points,
                                                    'raw_receipt'           => $row->receipt_pdf,
                                                    'created_at'            => $row->created_at,
                                                    'updated_at'            => $row->updated_at
                                                ]);
          });
        echo PHP_EOL;

    }

    public function down(): void {
        Schema::dropIfExists('receipts');
    }
}
