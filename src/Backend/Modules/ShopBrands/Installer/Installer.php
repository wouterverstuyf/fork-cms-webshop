<?php

namespace Backend\Modules\ShopBrands\Installer;

use Backend\Core\Installer\ModuleInstaller;

/**
 * Installer for the ShopBrands module
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
        $this->addModule('ShopBrands');

        // install the locale, this is set here beceause we need the module for this
        $this->importLocale(dirname(__FILE__) . '/Data/locale.xml');

        $this->setModuleRights(1, 'ShopBrands');

        $this->setActionRights(1, 'ShopBrands', 'Index');
        $this->setActionRights(1, 'ShopBrands', 'Add');
        $this->setActionRights(1, 'ShopBrands', 'Edit');
        $this->setActionRights(1, 'ShopBrands', 'Delete');

        $this->makeSearchable('ShopBrands');


        // add extra's
        $subnameID = $this->insertExtra('ShopBrands', 'block', 'ShopBrands', null, null, 'N', 1000);
        $this->insertExtra('ShopBrands', 'block', 'ShopBrandsDetail', 'Detail', null, 'N', 1001);

        $navigationModulesId = $this->setNavigation(null, 'Modules');
        $navigationModulesId = $this->setNavigation($navigationModulesId, 'Products');
        $navigationclassnameId = $this->setNavigation(
            $navigationModulesId,
            'ShopBrands',
            'shop_brands/index',
            array('shop_brands/add','shop_brands/edit')
        );

    }
}
