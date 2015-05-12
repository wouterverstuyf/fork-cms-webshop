<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('products', function($table)
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

			$table->boolean('hidden');
			$table->timestamp('publish_at');

			$table->integer('brand_id')->nullable();
			$table->integer('user_id');
			$table->integer('unit_id');

			$table->index('brand_id');
			$table->index('user_id');
			$table->index('unit_id');

			$table->integer('bought_count');

			$table->char('image', 255)->nullable();
			$table->char('sku', 255)->nullable();
			$table->char('barcode', 255)->nullable();

			$table->boolean('deleted');
			$table->timestamp('deleted_at')->nullable();

			$table->tinyInteger('age_start')->default(0);
			$table->tinyInteger('age_end')->default(100);

			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('products');
	}

}
