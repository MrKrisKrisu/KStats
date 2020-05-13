<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateReweCrowdsourcingCategoryView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("DROP VIEW IF EXISTS rewe_crowdsourcing_categories_view;");
        DB::statement("CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `rewe_crowdsourcing_categories_view` AS select `p`.`id` AS `product_id`,(select `rcc`.`category_id` from `rewe_crowdsourcing_categories` `rcc` where (`rcc`.`product_id` = `p`.`id`) group by `rcc`.`category_id` order by count(0) desc limit 1) AS `category_id` from `rewe_products` `p`");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS rewe_crowdsourcing_categories_view;");
    }
}
