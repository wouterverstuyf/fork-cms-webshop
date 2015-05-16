<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopProductProperties extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shop_product_properties', function($table)
		{
			$table->increments('id');
			$table->timestamp('created_on')->nullable();
			$table->timestamp('edited_on')->nullable();
		});

		Schema::create('shop_product_properties_content', function($table)
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
		Schema::dropIfExists('shop_product_properties');
		Schema::dropIfExists('shop_product_properties_content');
	}

}
