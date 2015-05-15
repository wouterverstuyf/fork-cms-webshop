<?php

namespace Backend\Modules\ShopDiscountCodes\Installer;

use Backend\Core\Installer\ModuleInstaller;

/**
 * Installer for the Shop Shipping module
 *
 * @author Frederik Heyninck <frederik@figure8.be>
 */
class Installer extends ModuleInstaller
{
    public function install()
    {
        // import the sql
        $this->importSQL(dirname(__FILE__) . '/Data/install.sql');

        // install the module in the database
        $this->addModule('ShopDiscountCodes');

        // install the locale, this is set here beceause we need the module for this
        $this->importLocale(dirname(__FILE__) . '/Data/locale.xml');

        $this->setModuleRights(1, 'ShopDiscountCodes');

        $this->setActionRights(1, 'ShopDiscountCodes', 'Index');
        $this->setActionRights(1, 'ShopDiscountCodes', 'Add');
        $this->setActionRights(1, 'ShopDiscountCodes', 'Edit');
        $this->setActionRights(1, 'ShopDiscountCodes', 'Delete');

        $navigationModulesId = $this->setNavigation(null, 'Modules');
        $navigationModulesId = $this->setNavigation($navigationModulesId, 'Shop', 'shop_orders/index');
        $navigationclassnameId = $this->setNavigation(
            $navigationModulesId,
            'ShopDiscountCodes',
            'shop_discount_codes/index',
            array('shop_discount_codes/add','shop_discount_codes/edit')
        );

    }
}
