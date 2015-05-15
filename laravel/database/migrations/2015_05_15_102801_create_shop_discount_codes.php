<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopDiscountCodes extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shop_discount_codes', function($table)
		{
			$table->increments('id');
			$table->char('name', 255);
			$table->char('code', 50)->unique();
			$table->float('discount')->nullable();
			$table->integer('limit')->nullable();
			$table->integer('used_count')->default(0);
			$table->date('from')->nullable();
			$table->date('until')->nullable();
			$table->enum('discount_type', ['value', 'pct'])->default('value');

			$table->timestamp('created_on')->nullable();
			$table->timestamp('edited_on')->nullable();

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('shop_discount_codes');
	}

}
