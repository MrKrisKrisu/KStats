<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBonPdfToReweBonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rewe_bons', function (Blueprint $table) {
            $table->text("raw_bon")
                ->comment("deprecated")
                ->change();
            $table->binary("receipt_pdf")
                ->nullable()
                ->after('raw_bon');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rewe_bons', function (Blueprint $table) {
            $table->text("raw_bon")
                ->comment("")
                ->change();
            $table->dropColumn('receipt_pdf');
        });
    }
}
