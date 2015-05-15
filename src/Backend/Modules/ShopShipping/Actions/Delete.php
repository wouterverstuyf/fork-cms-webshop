<?php

namespace Backend\Modules\ShopShipping\Actions;

use Backend\Core\Engine\Base\ActionDelete;
use Backend\Core\Engine\Model;
use Backend\Modules\ShopShipping\Engine\Model as BackendShopShippingModel;

/**
 * This is the delete-action, it deletes an item
 *
 * @author Frederik Heyninck <frederik@figure8.be>
 */
class Delete extends ActionDelete
{
    /**
     * Execute the action
     */
    public function execute()
    {
        $this->id = $this->getParameter('id', 'int');

        // does the item exist
        if ($this->id !== null && BackendShopShippingModel::exists($this->id)) {
            parent::execute();
            $this->record = (array) BackendShopShippingModel::get($this->id);

            BackendShopShippingModel::delete($this->id);

            Model::triggerEvent(
                $this->getModule(), 'after_delete',
                array('id' => $this->id)
            );

            $this->redirect(
                Model::createURLForAction('Index') . '&report=deleted&var=' .
                urlencode($this->record['country'])
            );
        }
        else $this->redirect(Model::createURLForAction('Index') . '&error=non-existing');
    }
}
