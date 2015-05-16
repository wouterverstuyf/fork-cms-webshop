<?php

namespace Backend\Modules\ShopProductProperties\Actions;

use Backend\Core\Engine\Base\ActionAdd;
use Backend\Core\Engine\Language;
use Backend\Core\Engine\Model;
use Backend\Modules\ShopProductProperties\Engine\Model as BackendShopProductPropertiesModel;

/**
 * This is the add-action, it will display a form to create a new item
 *
 * @author Frederik Heyninck <frederik@figure8.be>
 */
class AddValue extends ActionAdd
{
    /**
     * Execute the actions
     */
    public function execute()
    {
        parent::execute();

        BackendShopProductPropertiesModel::addValueForProperty($this->getParameter('id', 'int', null));

         $this->redirect(
            Model::createURLForAction('Edit') . '&report=added&id=' . $this->getParameter('id', 'int', null)
        );

        $this->parse();
        $this->display();
    }

  
    /**
     * Parse the page
     */
    protected function parse()
    {
        parent::parse();

    }
}
