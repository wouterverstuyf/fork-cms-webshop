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
			$table->integer('property_id');
			$table->index('property_id');
			$table->char('language', 4);
			$table->char('name', 50);
		});

		Schema::create('shop_product_properties_values', function($table)
		{
			$table->increments('id');
			$table->integer('property_id');
			$table->index('property_id');
			$table->integer('sequence')->default(0);

		});

		Schema::create('shop_product_properties_values_content', function($table)
		{
			$table->increments('id');

			$table->char('language', 4);
			$table->char('name', 50);

			$table->integer('property_id');
			$table->index('property_id');

			$table->integer('value_id');
			$table->index('value_id');

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
		Schema::dropIfExists('shop_product_properties_values');
		Schema::dropIfExists('shop_product_properties_values_content');
	}

}
