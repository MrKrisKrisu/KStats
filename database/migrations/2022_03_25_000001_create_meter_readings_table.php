<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void {
        Schema::create('meter_readings', static function(Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('meter_id');
            $table->timestamp('reading_at')->useCurrent();
            $table->decimal('value', 10, 3);
            $table->timestamps();

            $table->unique(['meter_id', 'reading_at']);
            $table->unique(['meter_id', 'value']);

            $table->foreign('meter_id')->references('id')->on('meters')->cascadeOnDelete();
        });
    }

    public function down(): void {
        Schema::dropIfExists('meter_readings');
    }
};
