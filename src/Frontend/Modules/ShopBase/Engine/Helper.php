<?php

namespace Frontend\Modules\ShopBase\Engine;

use Frontend\Core\Engine\Model as FrontendModel;
use Frontend\Core\Engine\Language;


/**
 * In this file we store all generic functions that we will be using in the ShopBase module
 *
 * @author Frederik Heyninck <frederik@figure8.be>
 */
class Helper
{
	public static function calculatePriceInclVat($priceExcl, $vatPct)
    {
    	return ($priceExcl * ((int) $vatPct + 1));
    }

    public static function calculatePriceExclVat($priceIncl, $vatPct)
    {
        return (($priceIncl / (100 + (int) $vatPct)) * 100);
    }

    public static function calculatePriceVat($priceExcl, $vatPct)
    {
        return ($priceExcl * ((int) $vatPct + 1)) - $priceExcl;
    }
}
