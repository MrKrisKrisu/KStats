<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReweCrowdsourcingCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rewe_crowdsourcing_categories', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('user_id')
                ->unsigned()
                ->index();
            $table->bigInteger('product_id')
                ->unsigned()
                ->index();
            $table->bigInteger('category_id')
                ->unsigned()
                ->nullable()
                ->index();

            $table->timestamps();

            $table->unique(['user_id', 'product_id']);

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('product_id')
                ->references('id')
                ->on('rewe_products')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('category_id')
                ->references('id')
                ->on('rewe_product_categories')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rewe_crowdsourcing_categories');
    }
}
