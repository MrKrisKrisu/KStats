<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReweBonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rewe_bons', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')
                ->references('id')->on('users')
                ->index()
                ->nullable();
            $table->integer('shop_id')
                ->references('id')->on('rewe_shops')
                ->index();
            $table->timestamp('timestamp_bon');
            $table->integer('bon_nr');
            $table->integer('cashier_nr');
            $table->integer('cashregister_nr')->nullable();
            $table->string('paymentmethod');
            $table->boolean('payed_cashless');
            $table->boolean('payed_contactless');
            $table->float('total');
            $table->integer('earned_payback_points');
            $table->text('raw_bon')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rewe_bons');
    }
}
