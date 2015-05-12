<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBrands extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('brands', function($table)
		{
			$table->increments('id');
			$table->char('image', 255)->nullable();
			$table->char('website', 255)->nullable();
			$table->boolean('hidden');
			$table->timestamps();
		});

		Schema::create('brands_content', function($table)
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
		Schema::drop('brands_content');
		Schema::drop('brands');

	}

}
