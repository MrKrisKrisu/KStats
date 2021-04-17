<?php

use App\Company;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration {

    public function up(): void {
        Schema::create('companies', function(Blueprint $table) {
            $table->id();
            $table->string('name')
                  ->unique();
            $table->string('wikidata_id')
                  ->unique()
                  ->nullable()
                  ->default(null);
            $table->timestamps();
        });

        Company::create(['name' => 'REWE', 'wikidata_id' => 'Q16968817']);
    }

    public function down(): void {
        Schema::dropIfExists('companies');
    }
}
