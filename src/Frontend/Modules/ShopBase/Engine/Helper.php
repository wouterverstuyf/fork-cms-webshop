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
    	return self::roundPrice(((float) $priceExcl * (((float) $vatPct + 100)) / 100));
    }

    public static function calculatePriceExclVat($priceIncl, $vatPct)
    {
        return self::roundPrice((((float) $priceIncl / (100 + (float) $vatPct)) * 100));
    }

    public static function calculatePriceVat($priceExcl, $vatPct)
    {
        return self::roundPrice(self::calculatePriceInclVat($priceExcl, $vatPct) - (float) $priceExcl);
    }

    public static function roundPrice($price , $precision = 4)
    {
        return number_format($price , $precision , '.', '');
    }
}
