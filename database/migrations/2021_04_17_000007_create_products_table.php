<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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

        echo "Migrate data to new structure: " . PHP_EOL;
        DB::table('rewe_products')
          ->orderBy('id')
          ->chunk(250, function($rows) {
              echo ".";
              foreach($rows as $row)
                  DB::table('products')->insert([
                                                    'id'         => $row->id,
                                                    'name'       => $row->name,
                                                    'created_at' => $row->created_at,
                                                    'updated_at' => $row->updated_at
                                                ]);
          });
        echo PHP_EOL;
    }

    public function down(): void {
        Schema::dropIfExists('products');
    }
}
