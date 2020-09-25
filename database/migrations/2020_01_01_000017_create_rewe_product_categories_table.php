<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReweProductCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rewe_product_categories', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->bigInteger('parent_id')
                ->nullable()
                ->unsigned()
                ->index();

            $table->timestamps();

            $table->foreign('parent_id')
                ->references('id')
                ->on('rewe_product_categories')
                ->onDelete('restrict')
                ->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rewe_product_categories');
    }
}
