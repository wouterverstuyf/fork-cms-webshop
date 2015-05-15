<?php

namespace Backend\Modules\ShopDiscountCodes\Actions;

use Backend\Core\Engine\Base\ActionIndex;
use Backend\Core\Engine\Authentication;
use Backend\Core\Engine\DataGridDB;
use Backend\Core\Engine\Language;
use Backend\Core\Engine\Model;
use Backend\Modules\ShopDiscountCodes\Engine\Model as BackendShopDiscountCodesModel;

/**
 * This is the index-action (default), it will display the overview of Shop Shipping posts
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
            BackendShopDiscountCodesModel::QRY_DATAGRID_BROWSE
        );

        $this->dataGrid->setColumnFunction(array(__CLASS__, 'setDiscount'), array('[discount]', '[discount_type]'), 'discount', true);

        $this->dataGrid->setColumnHidden('discount_type');


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

    public static function setDiscount($number, $type)
    {
        // redefin
        $number = (float) $number;
        $type = (string) $type;

        // type
        switch($type)
        {
            case 'value':
                return '€'. $number;
            break;

            case 'pct':
                return $number .'%';
            break;
        }

        // return
        return $number;
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
