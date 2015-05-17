<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopProductVariants extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shop_product_variants', function($table)
		{
			$table->bigIncrements('id');
			$table->bigInteger('product_id');
			$table->index('product_id');

			$table->integer('stock');

			$table->float('price_incl');
			$table->float('price_excl');
			$table->float('price_vat');
			$table->enum('price_is_incl_vat', ['N', 'Y'])->default('Y');

			$table->float('promo_price_incl')->nullable();
			$table->float('promo_price_excl')->nullable();
			$table->float('promo_price_vat')->nullable();
			$table->enum('promo_price_is_incl_vat', ['N', 'Y'])->default('Y');

			$table->string('sku', 255)->nullable();
			

			$table->integer('sequence')->default(0);

		});

		Schema::create('shop_product_variant_values', function($table)
		{
			$table->bigInteger('variant_id');
			$table->index('variant_id');

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
		Schema::dropIfExists('shop_product_variants');
		Schema::dropIfExists('shop_product_variant_values');
	}

}
