<?php

namespace Backend\Modules\ShopCategories\Installer;

use Backend\Core\Installer\ModuleInstaller;

/**
 * Installer for the ShopCategories module
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
        $this->addModule('ShopCategories');

        // install the locale, this is set here beceause we need the module for this
        $this->importLocale(dirname(__FILE__) . '/Data/locale.xml');

        $this->setModuleRights(1, 'ShopCategories');

        $this->setActionRights(1, 'ShopCategories', 'Index');
        $this->setActionRights(1, 'ShopCategories', 'Add');
        $this->setActionRights(1, 'ShopCategories', 'Edit');
        $this->setActionRights(1, 'ShopCategories', 'Delete');

        $this->makeSearchable('ShopCategories');

        $navigationModulesId = $this->setNavigation(null, 'Modules');
        $navigationModulesId = $this->setNavigation($navigationModulesId, 'Products');
        $navigationclassnameId = $this->setNavigation(
            $navigationModulesId,
            'ShopCategories',
            'shop_categories/index',
            array('shop_categories/add','shop_categories/edit')
        );

    }
}
