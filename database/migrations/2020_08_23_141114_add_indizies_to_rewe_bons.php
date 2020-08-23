<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndiziesToReweBons extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rewe_bons', function (Blueprint $table) {
            $table->index('timestamp_bon');
            $table->index('bon_nr');
            $table->index('paymentmethod');
            $table->index('payed_cashless');
            $table->index('payed_contactless');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rewe_bons', function (Blueprint $table) {
            $table->dropIndex('rewe_bons_timestamp_bon_index');
            $table->dropIndex('rewe_bons_bon_nr_index');
            $table->dropIndex('rewe_bons_paymentmethod_index');
            $table->dropIndex('rewe_bons_payed_cashless_index');
            $table->dropIndex('rewe_bons_payed_contactless_index');
            $table->dropIndex('rewe_bons_created_at_index');
        });
    }
}
