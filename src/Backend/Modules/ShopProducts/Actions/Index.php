<?php

namespace Backend\Modules\ShopProducts\Actions;

use Backend\Core\Engine\Base\ActionIndex;
use Backend\Core\Engine\Authentication;
use Backend\Core\Engine\DataGridDB;
use Backend\Core\Engine\Language;
use Backend\Core\Engine\Model;
use Backend\Modules\ShopProducts\Engine\Model as BackendShopProductsModel;

/**
 * This is the index-action (default), it will display the overview of ShopProducts posts
 *
 * @author Frederik Heyninck <frederik@figure8.be>
 */
class Index extends ActionIndex
{
    /**
     * Execute the action
     */
    public function execute()
    {
        parent::execute();
        $this->loadDataGrid();

        $this->parse();
        $this->display();
    }

    /**
     * Load the dataGrid
     */
    protected function loadDataGrid()
    {
        $this->dataGrid = new DataGridDB(
            BackendShopProductsModel::QRY_DATAGRID_BROWSE,
            Language::getWorkingLanguage()
        );


        // check if this action is allowed
        if (Authentication::isAllowedAction('Edit')) {
            $this->dataGrid->addColumn(
                'edit', null, Language::lbl('Edit'),
                Model::createURLForAction('Edit') . '&amp;id=[id]',
                Language::lbl('Edit')
            );
            $this->dataGrid->setColumnURL(
                'name', Model::createURLForAction('Edit') . '&amp;id=[id]'
            );
        }
    }

    /**
     * Parse the page
     */
    protected function parse()
    {
        // parse the dataGrid if there are results
        $this->tpl->assign('dataGrid', (string) $this->dataGrid->getContent());
    }
}
