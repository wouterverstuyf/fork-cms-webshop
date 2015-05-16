<?php

namespace Backend\Modules\ShopCategories\Actions;

use Backend\Core\Engine\Base\ActionIndex;
use Backend\Core\Engine\Authentication;
use Backend\Core\Engine\DataGridDB;
use Backend\Core\Engine\Language;
use Backend\Core\Engine\Model;
use Backend\Modules\ShopCategories\Engine\Model as BackendShopCategoriesModel;

/**
 * This is the index-action (default), it will display the overview of ShopCategories posts
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

        $this->child_of = $this->getParameter('child_of', 'int', null);

        $this->loadDataGrid();

        $this->parse();
        $this->display();

        \Spoon::dump(BackendShopCategoriesModel::getTreeByParent(array(2, 20)));
    }

    /**
     * Builds the query for this dataGrid
     *
     * @return array An array with two arguments containing the query and its parameters.
     */
    private function buildQuery()
    {
        // init var
        $parameters = array();
       
        $query = 'SELECT i.id, c.name, i.sequence
         FROM shop_categories AS i
         INNER JOIN shop_categories_content as c on i.id = c.category_id
         WHERE 1';

        $query .= ' AND c.language = ?';
        $parameters[] = LANGUAGE::getWorkingLanguage();

        if($this->child_of != NULL) {
            $query .= ' AND i.child_of = ?'; 
            $parameters[] = $this->child_of;

            /*$tree =  BackendShopCategoriesModel::getTreeByParent($this->child_of);

            foreach($tree as &$t){
                $t['selected'] = $t['id'] == $this->child_of;
            }

            $this->tpl->assign('tree', $tree);*/
            

        } else {
            $query .= ' AND i.child_of IS NULL';
        }

        $query .= ' ORDER BY i.sequence';

        // query + parameters
        return array($query, $parameters);
    }

    /**
     * Load the dataGrid
     */
    protected function loadDataGrid()
    {   
        list($query, $parameters) = $this->buildQuery();

        $this->dataGrid = new DataGridDB($query, $parameters);

        // drag and drop sequencing
        $this->dataGrid->enableSequenceByDragAndDrop();

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
