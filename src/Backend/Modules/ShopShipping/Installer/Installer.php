<?php

namespace Backend\Modules\ShopShipping\Installer;

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
        $this->addModule('ShopShipping');

        // install the locale, this is set here beceause we need the module for this
        $this->importLocale(dirname(__FILE__) . '/Data/locale.xml');

        $this->setModuleRights(1, 'ShopShipping');

        $this->setActionRights(1, 'ShopShipping', 'Index');
        $this->setActionRights(1, 'ShopShipping', 'Add');
        $this->setActionRights(1, 'ShopShipping', 'Edit');
        $this->setActionRights(1, 'ShopShipping', 'Delete');

        $navigationModulesId = $this->setNavigation(null, 'Modules');
        $navigationModulesId = $this->setNavigation($navigationModulesId, 'Shop', 'shop_orders/index');
        $navigationclassnameId = $this->setNavigation(
            $navigationModulesId,
            'ShopShipping',
            'shop_shipping/index',
            array('shop_shipping/add','shop_shipping/edit')
        );

    }
}
