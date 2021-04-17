<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopsTable extends Migration {

    public function up(): void {
        Schema::create('shops', function(Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('company_id');

            $table->string('name')
                  ->nullable();
            $table->string('address')
                  ->nullable();
            $table->string('postal_code', 5)
                  ->nullable();
            $table->string('city')
                  ->nullable();

            $table->enum('osm_type', ['node', 'way', 'relation'])
                  ->nullable();
            $table->unsignedBigInteger('osm_id')
                  ->nullable();

            $table->timestamps();

            $table->foreign('company_id')
                  ->references('id')
                  ->on('companies');
        });
    }

    public function down(): void {
        Schema::dropIfExists('shops');
    }
}
