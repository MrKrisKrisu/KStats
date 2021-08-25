<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePublicTransportComplaintsTable extends Migration {

    public function up(): void {
        Schema::create('public_transport_complaints', function(Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('journey_id')->nullable()->default(null);
            $table->unsignedBigInteger('card_id');
            $table->date('date');
            $table->decimal('cashback');
            $table->timestamps();

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->cascadeOnDelete();
            $table->foreign('journey_id')
                  ->references('id')
                  ->on('public_transport_journeys')
                  ->cascadeOnDelete();
            $table->foreign('card_id')
                  ->references('id')
                  ->on('public_transport_cards')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void {
        Schema::dropIfExists('public_transport_complaints');
    }
}
