<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopProductsCategoriesProducts extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shop_products_categories_products', function($table)
		{
			$table->integer('category_id');
			$table->bigInteger('product_id');
			$table->primary(['category_id', 'product_id']);
			$table->integer('sequence')->default(0);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('shop_products_categories_products');
	}

}
