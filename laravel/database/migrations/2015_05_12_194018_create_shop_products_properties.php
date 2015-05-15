<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopProductsProperties extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shop_products_properties', function($table)
		{
			$table->increments('id');
			$table->timestamps();
		});

		Schema::create('shop_products_properties_content', function($table)
		{
			$table->bigInteger('product_property_id');
			$table->index('product_property_id');
			$table->char('language', 4);
			$table->char('name', 255);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('shop_products_properties');
		Schema::dropIfExists('shop_products_properties_content');
	}

}
