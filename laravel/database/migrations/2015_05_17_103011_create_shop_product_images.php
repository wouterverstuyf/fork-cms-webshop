<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopProductImages extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shop_product_images', function($table)
		{
			$table->bigIncrements('id');

			$table->bigInteger('variant_id')->nullable();
			$table->index('variant_id');

			$table->bigInteger('product_id');
			$table->index('product_id');

			$table->integer('sequence')->default(0);

			$table->string('image', 255);

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('shop_product_images');
	}

}
