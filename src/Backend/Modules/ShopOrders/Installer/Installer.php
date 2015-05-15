<?php

namespace Backend\Modules\ShopOrders\Installer;

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
        $this->addModule('ShopOrders');

        // install the locale, this is set here beceause we need the module for this
        $this->importLocale(dirname(__FILE__) . '/Data/locale.xml');

        $this->setModuleRights(1, 'ShopOrders');

        $this->setActionRights(1, 'ShopOrders', 'Index');
        $this->setActionRights(1, 'ShopOrders', 'Add');
        $this->setActionRights(1, 'ShopOrders', 'Edit');
        $this->setActionRights(1, 'ShopOrders', 'Delete');

        $subnameID = $this->insertExtra('ShopOrders', 'block', 'ShopOrders', null, null, 'N', 1000);
        $this->insertExtra('ShopOrders', 'block', 'ShopOrdersDetail', 'Detail', null, 'N', 1001);

        $navigationModulesId = $this->setNavigation(null, 'Modules');
        $navigationModulesId = $this->setNavigation($navigationModulesId, 'Shop', 'shop_orders/index');
        $navigationclassnameId = $this->setNavigation(
            $navigationModulesId,
            'ShopOrders',
            'shop_orders/index',
            array('shop_orders/add','shop_orders/edit')
        );

    }
}
