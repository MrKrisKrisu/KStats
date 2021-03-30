<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFriendshipRequestsTable extends Migration {

    public function up(): void {
        Schema::create('friendship_requests', function(Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('requester_id');
            $table->unsignedBigInteger('user_id');

            $table->timestamps();

            $table->unique(['requester_id', 'user_id']);

            $table->foreign('requester_id')
                  ->references('id')
                  ->on('users')
                  ->cascadeOnDelete();
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void {
        Schema::dropIfExists('friendship_requests');
    }
}
