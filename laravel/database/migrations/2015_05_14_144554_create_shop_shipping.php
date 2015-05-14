<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopShipping extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shop_shipping', function($table)
		{
			$table->increments('id');
			$table->char('country', 5);
			$table->integer('duration');
			$table->float('price_incl');
			$table->float('price_excl');
			$table->float('price_vat');
			$table->float('vat_pct');
			$table->enum('add_vat_consumer', ['N', 'Y'])->default('N');
			$table->enum('add_vat_company', ['N', 'Y'])->default('N');
			$table->enum('hidden', ['N', 'Y'])->default('N');
			$table->enum('free_from', ['N', 'Y'])->default('N');
			$table->float('free_from_price');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('shop_shipping');
	}

}
