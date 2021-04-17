<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreatePaymentMethodsTable extends Migration {

    public function up(): void {
        Schema::create('payment_methods', function(Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        $query = DB::table('rewe_bons')->groupBy('paymentmethod')->select('paymentmethod')->get();
        foreach($query as $row) {
            DB::table('payment_methods')->insert([
                                                     'name'       => $row->paymentmethod,
                                                     'created_at' => DB::raw('CURRENT_TIMESTAMP'),
                                                     'updated_at' => DB::raw('CURRENT_TIMESTAMP'),
                                                 ]);
        }
    }

    public function down(): void {
        Schema::dropIfExists('payment_methods');
    }
}
