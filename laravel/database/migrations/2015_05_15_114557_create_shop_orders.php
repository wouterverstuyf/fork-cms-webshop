<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopOrders extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shop_orders', function($table)
		{
			$table->bigIncrements('id');
			$table->bigInteger('invoice_id');
			$table->char('hash', 50)->nullable();
			$table->integer('profile_id')->nullable();

			$table->enum('shipping_same_as_billing', ['N', 'Y'])->default('Y');
			
			$table->string('address_billing_firstname', 255);
			$table->string('address_billing_lastname', 255);
			$table->string('address_billing_email', 255);
			$table->string('address_billing_phone', 255);
			$table->string('address_billing_street', 255);
			$table->string('address_billing_number', 10);
			$table->string('address_billing_zip', 40);
			$table->string('address_billing_city', 40);
			$table->char('address_billing_country', 5);

			$table->string('address_shipping_firstname', 255)->nullable();
			$table->string('address_shipping_lastname', 255)->nullable();
			$table->string('address_shipping_phone', 255)->nullable();
			$table->string('address_shipping_street', 255)->nullable();
			$table->string('address_shipping_number', 10)->nullable();
			$table->string('address_shipping_zip', 40)->nullable();
			$table->string('address_shipping_city', 40)->nullable();
			$table->char('address_shipping_country', 5)->nullable();

			$table->string('company_name', 50)->nullable();
			$table->string('company_vat_number', 50)->nullable();

			$table->float('discount')->nullable();
			$table->enum('discount_type', ['value', 'pct'])->default('value');

			$table->double('discount_price_incl', 10, 4);
			$table->double('discount_price_excl', 10, 4);
			$table->double('discount_price_vat', 10, 4);

			$table->text('message')->nullable();

			$table->enum('status', ['open','payment_in_progress','payment_cancelled','paid','processed','shipped','closed','cancelled'])->default('open');

			$table->datetime('paid_on')->nullable();
			$table->datetime('shipped_on')->nullable();
			$table->datetime('payment_cancelled_on')->nullable();
			$table->datetime('processed_on')->nullable();
			$table->datetime('closed_on')->nullable();
			$table->datetime('cancelled_on')->nullable();
			$table->datetime('invoice_created_on')->nullable();

			$table->enum('gift_wrap', ['N', 'Y'])->default('N');

			$table->double('shipping_price_incl', 10, 4);
			$table->double('shipping_price_excl', 10, 4);
			$table->double('shipping_price_vat', 10, 4);
			$table->enum('shipping_add_vat_consumer', ['N', 'Y'])->default('N');
			$table->enum('shipping_add_vat_company', ['N', 'Y'])->default('N');
			$table->float('shipping_free_from_price')->nullable();

			$table->float('vat_pct');

			$table->double('total_price_incl', 10, 4);
			$table->double('total_price_excl', 10, 4);
			$table->double('total_price_vat', 10, 4);

			$table->enum('delivery_type', ['ship', 'pickup'])->default('ship');
			$table->datetime('pickup_on')->nullable();

			$table->timestamp('created_on')->nullable();
			$table->timestamp('edited_on')->nullable();

		});

		Schema::create('shop_order_products', function($table)
		{
			$table->bigInteger('order_id');
			$table->index('order_id');

			$table->bigInteger('product_id');
			$table->index('product_id');

			$table->integer('amount');
			$table->double('price_excl', 10, 4);

			$table->text('variation_data')->nullable();
			$table->text('message')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('shop_orders');
		Schema::dropIfExists('shop_order_products');
	}

}
