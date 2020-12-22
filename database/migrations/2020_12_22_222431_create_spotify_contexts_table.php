<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use JetBrains\PhpStorm\NoReturn;

class CreateSpotifyContextsTable extends Migration {

    public function up(): void {
        Schema::create('spotify_contexts', function(Blueprint $table) {
            $table->id();
            $table->string('uri')->unique();
            $table->timestamps();
        });

        DB::table('spotify_contexts')->insertUsing(['uri'], DB::table('spotify_play_activities')
                                                              ->select(['context_uri'])
                                                              ->where('context_uri', '<>', null)
                                                              ->distinct());

        DB::table('spotify_contexts')->update([
                                                  'created_at' => Carbon::now(),
                                                  'updated_at' => Carbon::now(),
                                              ]);

        Schema::table('spotify_play_activities', function(Blueprint $table) {
            $table->unsignedBigInteger('context_id')
                  ->nullable()
                  ->default(null)
                  ->index()
                  ->after('progress_ms');
        });

        DB::table('spotify_play_activities')->update([
                                                         'context_id' => DB::raw('(SELECT id FROM spotify_contexts WHERE spotify_contexts.uri = spotify_play_activities.context_uri)')
                                                     ]);

        Schema::table('spotify_play_activities', function(Blueprint $table) {
            $table->dropColumn('context');
            $table->dropColumn('context_uri');
        });
    }

    #[NoReturn]
    public function down(): void {
        dd("Write Rollback-Script before continue...!");
        //Schema::dropIfExists('spotify_contexts');
    }
}
