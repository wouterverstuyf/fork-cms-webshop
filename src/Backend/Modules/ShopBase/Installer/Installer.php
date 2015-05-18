<?php

namespace Backend\Modules\ShopBase\Installer;

use Backend\Core\Installer\ModuleInstaller;
use Backend\Modules\ShopBase\Engine\Api as Api;


/**
 * Installer for the ShopBase module
 *
 * @author Frederik Heyninck <frederik@figure8.be>
 */
class Installer extends ModuleInstaller
{
    public function install()
    {
        // import the sql
        //$this->importSQL(dirname(__FILE__) . '/Data/install.sql');

        // install the module in the database
        $this->addModule('ShopBase');

        // install the locale, this is set here beceause we need the module for this
        $this->importLocale(dirname(__FILE__) . '/Data/locale.xml');

        $this->setModuleRights(1, 'ShopBase');

        self::doApiCall();
    }


    private function doApiCall()
    {
        try
        {
            // build parameters
            $parameters = array(
                'site_domain' => isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'fork.local',
                'ip' => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null,
                'type' => 'module',
                'name' => 'ShopBase',
                'version' => '1.0',
                'email' => \SpoonSession::get('email'),
                'license_name' => '',
                'license_key' => '',
                'license_domain' => ''
            );
        
            // call
            $api = new Api();
            $api->setApiURL('http://www.fork-cms-extensions.com/api/1.0');
            $return = $api->doCall('products.insertProductInstallation', $parameters, false);
            $this->setSetting('ShopBase', 'ApiCallId', (string) $return->data->id);
        } 
        catch(Exception $e){
        
        }
    }
}
