<?php

namespace Backend\Modules\ShopProductProperties\Ajax;

use Backend\Core\Engine\Base\AjaxAction;
use Backend\Modules\ShopProductProperties\Engine\Model as BackendShopProductPropertiesModel;

/**
 * Alters the sequence of ShopProductProperties articles
 *
 * @author Frederik Heyninck <frederik@figure8.be>
 */
class Sequence extends AjaxAction
{
    public function execute()
    {
        parent::execute();

        // get parameters
        $newIdSequence = trim(\SpoonFilter::getPostValue('new_id_sequence', null, '', 'string'));

        // list id
        $ids = (array) explode(',', rtrim($newIdSequence, ','));

        // loop id's and set new sequence
        foreach ($ids as $i => $id) {
            $item['id'] = $id;
            $item['sequence'] = $i + 1;

            // update sequence
            if (BackendShopProductPropertiesModel::existsValue($id)) {
                BackendShopProductPropertiesModel::updateValue($item);
            }
        }

        // success output
        $this->output(self::OK, null, 'sequence updated');
    }
}
