<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateReweCrowdsourcingVegetarianView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("DROP VIEW IF EXISTS rewe_crowdsourcing_vegetarian_view;");
        DB::statement("CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `rewe_crowdsourcing_vegetarian_view` AS 
        select `p`.`id` AS `product_id`,(select `rcc`.`vegetarian` from `rewe_crowdsourcing_vegetarians` `rcc` where (`rcc`.`product_id` = `p`.`id`) group by `rcc`.`vegetarian` order by count(0) desc limit 1) AS `vegetarian` from `rewe_products` `p`");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS rewe_crowdsourcing_vegetarian_view;");
    }
}
