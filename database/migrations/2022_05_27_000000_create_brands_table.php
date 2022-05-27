<?php

use App\Models\Brand;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void {
        Schema::create('brands', static function(Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('wikidata_id')->nullable()->unique();
            $table->string('primary_color', 7)->nullable();
            $table->text('vector_logo')->nullable();
            $table->timestamps();
        });

        Brand::create([
                          'name'          => 'REWE',
                          'wikidata_id'   => 'Q16968817',
                          'primary_color' => '#CC071E',
                          'vector_logo'   => self::$xmlLogo,
                      ]);
    }

    public function down(): void {
        Schema::dropIfExists('brands');
    }

    private static $xmlLogo = '<svg id="Ebene_1" data-name="Ebene 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 599.81 208.4"><defs><style>.cls-1{fill:#cc071e;}.cls-2{fill:#fff;}</style></defs><title>Zeichenfläche 2</title><rect class="cls-1" width="599.81" height="208.4"/><path class="cls-2" d="M64.56,175.26c7.92,0,11.67-3.75,11.67-11.46v-42.1h.42L101.25,164c4.59,7.92,8.55,11.26,15.63,11.26h24.8c6,0,11-2.29,11-7.09q0-2.81-2.5-6.88l-30.43-45C139.39,109,147.93,95,147.93,78.34c0-28.14-16.67-44.81-60.44-44.81H44.76C37,33.52,33.3,37.28,33.3,45.2v118.6c0,7.71,3.75,11.46,11.46,11.46ZM76.23,95.64V66H85.4c12.3,0,17.51,5.21,17.51,14.8S97.7,95.64,85.4,95.64Zm180.92,79.62c7.92,0,11.67-3.75,11.67-11.46V153.58c0-7.92-3.75-11.67-11.67-11.67H210.46V120.65h34.18c7.92,0,11.67-3.75,11.67-11.46V99c0-7.92-3.75-11.67-11.67-11.67H210.46V66.87H250.9c7.92,0,11.67-3.75,11.67-11.46V45.2c0-7.92-3.75-11.67-11.67-11.67H178.16c-7.71,0-11.46,3.75-11.46,11.67v118.6c0,7.71,3.75,11.46,11.46,11.46ZM388,44.78c-1.67-7.92-5.63-11.26-14.38-11.26H355.95c-8.75,0-12.71,3.33-14.38,11.26L327.39,114.4H327L315.51,44.78c-1.25-7.92-5.21-11.26-14.38-11.26H284c-7.09,0-11,3.13-11,9.38a20,20,0,0,0,.63,4.58l27.3,116.3c1.88,7.71,5.63,11.46,15.42,11.46h17.09c8.75,0,12.71-3.34,14.38-11.26l15.63-76.08h.42L379.5,164c1.67,7.92,5.63,11.26,14.38,11.26H411c9.8,0,13.55-3.75,15.42-11.46l27.3-116.3a19.9,19.9,0,0,0,.63-4.58c0-6.25-4.17-9.38-11-9.38H428.69c-9.17,0-13.13,3.33-14.38,11.26L402.84,114.4h-.42L388,44.78ZM555.21,175.26c7.92,0,11.67-3.75,11.67-11.46V153.58c0-7.92-3.75-11.67-11.67-11.67H508.52V120.65H542.7c7.92,0,11.67-3.75,11.67-11.46V99c0-7.92-3.75-11.67-11.67-11.67H508.52V66.87H549c7.92,0,11.67-3.75,11.67-11.46V45.2c0-7.92-3.75-11.67-11.67-11.67H476.21c-7.71,0-11.46,3.75-11.46,11.67v118.6c0,7.71,3.75,11.46,11.46,11.46Z"/></svg>';
};
