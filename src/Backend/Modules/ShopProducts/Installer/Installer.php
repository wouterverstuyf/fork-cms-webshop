<?php

namespace Backend\Modules\ShopProducts\Installer;

use Backend\Core\Installer\ModuleInstaller;

/**
 * Installer for the ShopProducts module
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
        $this->addModule('ShopProducts');

        // install the locale, this is set here beceause we need the module for this
        $this->importLocale(dirname(__FILE__) . '/Data/locale.xml');

        $this->setModuleRights(1, 'ShopProducts');

        $this->setActionRights(1, 'ShopProducts', 'Index');
        $this->setActionRights(1, 'ShopProducts', 'Add');
        $this->setActionRights(1, 'ShopProducts', 'Edit');
        $this->setActionRights(1, 'ShopProducts', 'Delete');

        $this->makeSearchable('ShopProducts');


        // add extra's
        $subnameID = $this->insertExtra('ShopProducts', 'block', 'ShopProducts', null, null, 'N', 1000);
        $this->insertExtra('ShopProducts', 'block', 'ShopProductsDetail', 'Detail', null, 'N', 1001);

        $navigationModulesId = $this->setNavigation(null, 'Modules');
        $navigationModulesId = $this->setNavigation($navigationModulesId, 'Products', 'shop_products/index');
        $navigationclassnameId = $this->setNavigation(
            $navigationModulesId,
            'ShopProducts',
            'shop_products/index',
            array('shop_products/add','shop_products/edit')
        );

    }
}
