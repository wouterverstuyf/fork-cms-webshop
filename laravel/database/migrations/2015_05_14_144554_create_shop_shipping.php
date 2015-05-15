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
			$table->integer('duration')->nullable();
			$table->double('price_incl', 10, 4);
			$table->double('price_excl', 10, 4);
			$table->double('price_vat', 10, 4);
			$table->float('vat_pct');
			$table->enum('add_vat_consumer', ['N', 'Y'])->default('N');
			$table->enum('add_vat_company', ['N', 'Y'])->default('N');
			$table->enum('hidden', ['N', 'Y'])->default('N');
			$table->float('free_from_price')->nullable();
			$table->enum('price_is_incl_vat', ['N', 'Y'])->default('Y');

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
		Schema::dropIfExists('shop_shipping');
	}

}
