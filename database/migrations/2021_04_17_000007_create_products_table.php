<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration {

    public function up(): void {
        Schema::create('products', function(Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->unsignedBigInteger('user_id')
                  ->nullable()
                  ->default(null);
            $table->unsignedBigInteger('product_type_id')
                  ->nullable()
                  ->default(null);

            $table->timestamps();

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->cascadeOnDelete();
            $table->foreign('product_type_id')
                  ->references('id')
                  ->on('product_types');

            $table->unique(['user_id', 'name']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('products');
    }
}
