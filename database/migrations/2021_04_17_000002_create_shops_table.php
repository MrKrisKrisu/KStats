<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateShopsTable extends Migration {

    public function up(): void {
        Schema::create('shops', function(Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('internal_shop_id');

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

            $table->unique(['company_id', 'internal_shop_id']);

            $table->foreign('company_id')
                  ->references('id')
                  ->on('companies');
        });

        foreach(DB::table('rewe_shops')->get() as $row) {
            DB::table('shops')->insert([
                                           'id'               => $row->id,
                                           'company_id'       => 1,
                                           'internal_shop_id' => $row->id,
                                           'name'             => $row->name,
                                           'address'          => $row->address,
                                           'postal_code'      => $row->zip,
                                           'city'             => $row->city,
                                           'created_at'       => $row->created_at,
                                           'updated_at'       => $row->updated_at
                                       ]);
        }
    }

    public function down(): void {
        Schema::dropIfExists('shops');
    }
}
