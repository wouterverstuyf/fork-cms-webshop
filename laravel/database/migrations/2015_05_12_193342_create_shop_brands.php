<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopBrands extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shop_brands', function($table)
		{
			$table->increments('id');
			$table->char('image', 255)->nullable();
			$table->char('website', 255)->nullable();
			$table->enum('hidden', ['N', 'Y'])->default('N');
			$table->timestamp('created_on')->nullable();
			$table->timestamp('edited_on')->nullable();
		});

		Schema::create('shop_brands_content', function($table)
		{
			$table->bigInteger('brand_id');
			$table->index('brand_id');
			$table->char('language', 4);
			$table->char('name', 255);
			$table->text('description')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('shop_brands_content');
		Schema::drop('shop_brands');
	}

}
