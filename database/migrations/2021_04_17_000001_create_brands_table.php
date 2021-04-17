<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBrandsTable extends Migration {

    public function up(): void {
        Schema::create('brands', function(Blueprint $table) {
            $table->id();
            $table->string('name')
                  ->unique();
            $table->string('wikidata_id')
                  ->unique()
                  ->nullable()
                  ->default(null);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('brands');
    }
}
