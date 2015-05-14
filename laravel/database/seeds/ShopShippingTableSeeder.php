<?php

namespace database\seeds;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class ShopShippingTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */

	private function round($price)
	{
		return number_format($price, 4, '.', '');
	}

	public function run()
	{
		DB::table('shop_shipping')->delete();

		$price_vat = 21;
		$price_incl = 10;
		$price_excl = $price_incl / (($price_vat + 100) / 100) ;
		$vat_pct = $price_incl - $price_excl ;
        DB::table('shop_shipping')->insert(
	        array(
	                array('country' => 'BE'),
	                array('duration' => 2),
	                array('price_incl' => self::round($price_incl)), 
	                array('price_excl' => self::round($price_excl)), 
	                array('price_vat' => self::round($price_vat)), 
	                array('vat_pct' => self::round($vat_pct)),    
	                array('add_vat_consumer' => 'Y'), 
	                array('add_vat_company' => 'Y'), 
	                array('hidden' => 'N'),   
	                array('free_from_price' => 100),
	                array('price_is_incl_vat' => 'Y'),
	                array('created_on' => date('Y-m-d H:i:s')),
	                array('edited_on' => date('Y-m-d H:i:s')),                 
	        ));
	}

}
