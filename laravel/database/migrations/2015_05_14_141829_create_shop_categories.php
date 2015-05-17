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
			$table->string('image', 255)->nullable();

			//$table->text('path')->nullable();
			//$table->index('path');

			$table->integer('child_of')->nullable();
			$table->index('child_of');

			$table->enum('hidden', ['N', 'Y'])->default('N');

			$table->integer('products_count')->default(0);
			$table->integer('children_count')->default(0);

			$table->integer('sequence')->default(0);

			$table->timestamp('created_on')->nullable();
			$table->timestamp('edited_on')->nullable();
		});

		Schema::create('shop_category_content', function($table)
		{
			$table->bigInteger('category_id');
			$table->index('category_id');
			$table->string('language', 4);
			$table->string('name', 255);
			$table->string('url', 255);
			$table->text('description')->nullable();
		});

		Schema::create('shop_categories_treepaths', function($table)
		{
			$table->integer('ancestor');
			$table->integer('descendant');
			$table->primary(['ancestor', 'descendant']);
			$table->integer('length')->default(0);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('shop_category_content');
		Schema::dropIfExists('shop_categories');
		Schema::dropIfExists('shop_categories_treepaths');
	}

}
