<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopCategories extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shop_categories', function($table)
		{
			$table->increments('id');
			$table->char('image', 255)->nullable();

			$table->text('path')->nullable();
			//$table->index('path');

			$table->integer('parent_id')->default(0);
			$table->index('parent_id');

			$table->enum('hidden', ['N', 'Y'])->default('N');

			$table->integer('products_count')->default(0);
			$table->integer('children_count')->default(0);

			$table->timestamp('created_on')->nullable();
			$table->timestamp('edited_on')->nullable();
		});

		Schema::create('shop_categories_content', function($table)
		{
			$table->bigInteger('category_id');
			$table->index('category_id');
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
		Schema::drop('shop_categories_content');
		Schema::drop('shop_categories');
	}

}