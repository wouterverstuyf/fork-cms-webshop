<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsProperties extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('products_properties', function($table)
		{
			$table->increments('id');
			$table->timestamps();
		});

		Schema::create('products_properties_content', function($table)
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
		Schema::drop('products_properties');
		Schema::drop('products_properties_content');
	}

}
