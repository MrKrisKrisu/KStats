<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReweBonsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('rewe_bons', function(Blueprint $table) {
            $table->id();

            $table->bigInteger('user_id')
                  ->unsigned()
                  ->index()
                  ->nullable();
            $table->bigInteger('shop_id')
                  ->unsigned()
                  ->index();
            $table->timestamp('timestamp_bon')
                  ->index();
            $table->integer('bon_nr')
                  ->index();
            $table->integer('cashier_nr');
            $table->integer('cashregister_nr')->nullable();
            $table->string('paymentmethod')
                  ->index();
            $table->boolean('payed_cashless')
                  ->index();
            $table->boolean('payed_contactless')
                  ->index();
            $table->float('total')
                  ->index();
            $table->integer('earned_payback_points');
            $table->text('raw_bon')->nullable()->comment('deprecated');
            $table->binary("receipt_pdf")
                  ->nullable();

            $table->timestamps();
            $table->index('created_at');

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->foreign('shop_id')
                  ->references('id')
                  ->on('rewe_shops')
                  ->onDelete('restrict')
                  ->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('rewe_bons');
    }
}
