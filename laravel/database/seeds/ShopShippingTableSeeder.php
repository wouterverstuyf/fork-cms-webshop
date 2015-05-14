<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class ShopShippingTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('shop_shipping')->delete();

        DB::table('shop_shipping')->insert(
	        array(
	                array('name' => 'admin'),
	                array('name' => 'user'),
	                array('name' => 'moderator'),                                
	        ));
	}

}
