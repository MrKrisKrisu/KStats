<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReweBonPositionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rewe_bon_positions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('bon_id')
                ->references('id')->on('rewe_bons')
                ->index();

            $table->integer('product_id')
                ->references('id')->on('rewe_products')
                ->index();

            $table->integer('amount')->nullable();
            $table->float('weight', 8, 3)->nullable();
            $table->float('single_price', 8, 2);

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
        Schema::dropIfExists('rewe_bon_positions');
    }
}
