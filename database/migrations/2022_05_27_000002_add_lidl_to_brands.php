<?php

use App\Models\Brand;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void {
        Brand::create([
                          'name'          => 'Lidl',
                          'wikidata_id'   => 'Q151954',
                          'primary_color' => '#TODO',
                          'vector_logo'   => self::$xmlLogo,
                      ]);
    }

    public function down(): void {
        Brand::where('name', 'Lidl')->first()->delete();
    }

    private static $xmlLogo = '';
};
