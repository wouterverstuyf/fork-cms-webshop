<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopProductsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('shop_products', function($table)
		{
			$table->bigIncrements('id');

			$table->integer('stock');
			$table->boolean('track_stock');
			$table->boolean('allow_purchase_when_out_of_stock');

			$table->boolean('featured');
			$table->boolean('has_variations');	

			$table->float('price_incl');
			$table->float('price_excl');
			$table->float('price_vat');

			$table->float('promo_price_incl')->nullable();
			$table->float('promo_price_excl')->nullable();
			$table->float('promo_price_vat')->nullable();
			$table->dateTime('promo_from')->nullable();
			$table->dateTime('promo_until')->nullable();

			$table->dateTime('new_from')->nullable();
			$table->dateTime('new_until')->nullable();

			$table->enum('hidden', ['N', 'Y'])->default('N');
			$table->timestamp('publish_on');

			$table->integer('brand_id')->nullable();
			$table->integer('user_id');
			$table->integer('unit_id')->nullable();

			$table->index('brand_id');
			$table->index('user_id');
			$table->index('unit_id');

			$table->integer('bought_count')->default(0);

			$table->char('image', 255)->nullable();
			$table->char('sku', 255)->nullable();
			$table->char('barcode', 255)->nullable();

			$table->boolean('deleted');
			$table->timestamp('deleted_on')->nullable();

			$table->tinyInteger('age_start')->default(0);
			$table->tinyInteger('age_end')->default(100);

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
		Schema::drop('shop_products');
	}

}
