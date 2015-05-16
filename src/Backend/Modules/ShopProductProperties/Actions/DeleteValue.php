<?php

namespace Backend\Modules\ShopProductProperties\Actions;

use Backend\Core\Engine\Base\ActionDelete;
use Backend\Core\Engine\Model;
use Backend\Modules\ShopProductProperties\Engine\Model as BackendShopProductPropertiesModel;

/**
 * This is the delete-action, it deletes an item
 *
 * @author Frederik Heyninck <frederik@figure8.be>
 */
class DeleteValue extends ActionDelete
{
    /**
     * Execute the action
     */
    public function execute()
    {
        $this->id = $this->getParameter('id', 'int');

        // does the item exist
        if ($this->id !== null && BackendShopProductPropertiesModel::existsValue($this->id)) {
            parent::execute();
            $this->record = (array) BackendShopProductPropertiesModel::getValue($this->id);

            BackendShopProductPropertiesModel::deleteValue($this->id);

            Model::triggerEvent(
                $this->getModule(), 'after_delete',
                array('id' => $this->id)
            );

            $this->redirect(
                Model::createURLForAction('Edit') . '&report=deleted&id=' .  $this->record['property_id'] 
            );
        }
        else $this->redirect(Model::createURLForAction('Index') . '&error=non-existing');
    }
}
