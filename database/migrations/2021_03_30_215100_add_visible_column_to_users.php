<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVisibleColumnToUsers extends Migration {

    public function up(): void {
        Schema::table('users', function(Blueprint $table) {
            $table->boolean('visible')
                  ->comment('user is visible to others')
                  ->default(0)
                  ->after('password');
        });
    }

    public function down(): void {
        Schema::table('users', function(Blueprint $table) {
            $table->dropColumn('visible');
        });
    }
}
