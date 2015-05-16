<?php

namespace Backend\Modules\ShopProductProperties\Ajax;

use Common\Uri as CommonUri;

use Backend\Core\Engine\Base\AjaxAction as BackendBaseAJAXAction;
use Backend\Core\Engine\Language as BL;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\ShopProductProperties\Engine\Model as BackendShopProductPropertiesModel;

/**
 * This is the save-action
 *
 * @author Frederik Heyninck <frederik@figure8.be>
 */
class SaveValue extends BackendBaseAJAXAction
{
    /**
     * Execute the action
     */
    public function execute()
    {
        parent::execute();

        // get possible languages
        $possibleLanguages = BL::getWorkingLanguages();

        // get parameters
        $language = \SpoonFilter::getPostValue('language', array_keys($possibleLanguages), null, 'string');
        $value = \SpoonFilter::getPostValue('value', null, null, 'string');
        $value_id = \SpoonFilter::getPostValue('value_id', null, null, 'string');

        // validate values
        if (trim($value) == '' || $language == '' || $value_id == '') $error = BL::err('InvalidValue');

        // no error?
        if (!isset($error)) {
            // build item
            $item['language'] = $language;
            $item['value_id'] = $value_id;
            $item['value'] = $value;


            // does the translation exist?
            if (BackendShopProductPropertiesModel::existsValueContentByValue($value, $language, $value_id)) {
                // add the id to the item
                $item['id'] = (int) BackendShopProductPropertiesModel::getByValueContentName($value, $language, $value_id);

                // update in db
                BackendShopProductPropertiesModel::updateValueContent($item);
            }

            // doesn't exist yet
            else {
                // insert in db
                BackendShopProductPropertiesModel::insertValueContent($item);
            }

            // output OK
            $this->output(self::OK);
        }

        // output the error
        else $this->output(self::ERROR, null, $error);
    }
}
