<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePublicTransportJourneysTable extends Migration {

    public function up(): void {
        Schema::create('public_transport_journeys', function(Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('public_transport_card_id');
            $table->string('origin');
            $table->string('destination');
            $table->decimal('price_without_card');
            $table->decimal('price_with_card');
            $table->timestamps();

            $table->foreign('public_transport_card_id')
                  ->references('id')
                  ->on('public_transport_cards')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void {
        Schema::dropIfExists('public_transport_journeys');
    }
}
