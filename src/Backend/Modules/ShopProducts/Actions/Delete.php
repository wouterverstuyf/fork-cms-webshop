<?php

namespace Backend\Modules\ShopProducts\Actions;

use Backend\Core\Engine\Base\ActionDelete;
use Backend\Core\Engine\Model;
use Backend\Modules\ShopProducts\Engine\Model as BackendShopProductsModel;

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
        if ($this->id !== null && BackendShopProductsModel::exists($this->id)) {
            parent::execute();
            $this->record = (array) BackendShopProductsModel::get($this->id);
            Model::deleteThumbnails(FRONTEND_FILES_PATH . '/' . $this->getModule() . '/image',  $this->record['image']);

            BackendShopProductsModel::delete($this->id);

            Model::triggerEvent(
                $this->getModule(), 'after_delete',
                array('id' => $this->id)
            );

            $this->redirect(
                Model::createURLForAction('Index') . '&report=deleted'
            );
        }
        else $this->redirect(Model::createURLForAction('Index') . '&error=non-existing');
    }
}
