<?php

namespace Backend\Modules\ShopProductProperties\Installer;

use Backend\Core\Installer\ModuleInstaller;

/**
 * Installer for the ShopProductProperties module
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
        $this->addModule('ShopProductProperties');

        // install the locale, this is set here beceause we need the module for this
        $this->importLocale(dirname(__FILE__) . '/Data/locale.xml');

        $this->setModuleRights(1, 'ShopProductProperties');

        $this->setActionRights(1, 'ShopProductProperties', 'Index');
        $this->setActionRights(1, 'ShopProductProperties', 'Add');
        $this->setActionRights(1, 'ShopProductProperties', 'Edit');
        $this->setActionRights(1, 'ShopProductProperties', 'Delete');
        $this->setActionRights(1, 'ShopProductProperties', 'AddValue');

        $navigationModulesId = $this->setNavigation(null, 'Modules');
        $navigationModulesId = $this->setNavigation($navigationModulesId, 'Products');
        $navigationclassnameId = $this->setNavigation(
            $navigationModulesId,
            'ShopProductProperties',
            'shop_product_properties/index',
            array('shop_product_properties/add','shop_product_properties/edit')
        );

    }
}
