<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReweCrowdsourcingVegetarianTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rewe_crowdsourcing_vegetarians', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')
                ->references('id')->on('users')
                ->index();
            $table->integer('product_id')
                ->references('id')->on('rewe_products')
                ->index();
            $table->boolean('vegetarian')
                ->nullable();
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
        Schema::dropIfExists('rewe_crowdsourcing_vegetarians');
    }
}
