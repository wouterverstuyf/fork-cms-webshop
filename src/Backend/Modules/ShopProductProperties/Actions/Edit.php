<?php

namespace Backend\Modules\ShopProductProperties\Actions;

use Backend\Core\Engine\Base\ActionEdit;
use Backend\Core\Engine\Form;
use Backend\Core\Engine\Language;
use Backend\Core\Engine\Model;
use Backend\Modules\ShopProductProperties\Engine\Model as BackendShopProductPropertiesModel;
use Backend\Modules\Search\Engine\Model as BackendSearchModel;
use Backend\Core\Engine\DataGridArray;
use Backend\Core\Engine\Authentication;
use Backend\Modules\ShopBase\Engine\Helper as ShopHelper;

/**
 * This is the edit-action, it will display a form with the item data to edit
 *
 * @author Frederik Heyninck <frederik@figure8.be>
 */
class Edit extends ActionEdit
{
    /**
     * Execute the action
     */
    public function execute()
    {
        parent::execute();

        $this->languages = ShopHelper::getLanguages();

        $this->loadData();
        $this->loadDataGrid();
        $this->loadForm();
        $this->validateForm();

        $this->parse();
        $this->display();
    }

    /**
     * Load the item data
     */
    protected function loadData()
    {
        $this->id = $this->getParameter('id', 'int', null);
        if ($this->id == null || !BackendShopProductPropertiesModel::exists($this->id)) {
            $this->redirect(
                Model::createURLForAction('Index') . '&error=non-existing'
            );
        }

        $this->record = BackendShopProductPropertiesModel::get($this->id);
        $this->valuesDataGrid = BackendShopProductPropertiesModel::getValuesForDatagrid($this->id);

       
    }

    /**
     * Load the form
     */
    protected function loadForm()
    {
        // create form
        $this->frm = new Form('edit');

        foreach($this->languages as &$language)
        {
            $language['formElements']['txtName'] = $this->frm->addText('name_'. $language['abbreviation'], isset($this->record['content'][$language['abbreviation']]['name']) ? $this->record['content'][$language['abbreviation']]['name'] : '', null, 'inputText title');
        }
    }

    /**
     * Parse the page
     */
    protected function parse()
    {
        parent::parse();

        $this->tpl->assign('languages', $this->languages);
        $this->tpl->assign('record', $this->record);
        // parse the dataGrid if there are results
        $this->tpl->assign('dataGrid', (string) $this->dataGrid->getContent());
    }


    /**
     * Load the dataGrid
     */
    protected function loadDataGrid()
    {
        $this->dataGrid = new DataGridArray($this->valuesDataGrid);

        $this->dataGrid->addColumn(
            'delete', null, Language::lbl('Delete'),
            Model::createURLForAction('DeleteValue') . '&amp;id=[id]',
            Language::lbl('Delete')
        );

        $this->dataGrid->enableSequenceByDragAndDrop();

        //$this->dataGrid->setColumnsHidden(array('value_id'));
        /*$this->dataGrid->setColumnURL(
            'name', Model::createURLForAction('Delete') . '&amp;id=[id]'
        );*/


            // set column attributes for each language
            foreach ($this->languages as $lang) {

                //$this->dataGrid->setColumnsHidden('value_content_id_' . $lang['abbreviation']);
                // add a class for the inline edit
                $this->dataGrid->setColumnAttributes($lang['abbreviation'], array('class' => 'translationValue'));
                // add attributes, so the inline editing has all the needed data
                $this->dataGrid->setColumnAttributes(
                    $lang['abbreviation'],
                    array(
                        'data-id' => '{language: \'' . $lang['abbreviation'] . '\', value_id:\'[id]\'}'
                    )
                );
                
            }
        
    }

    /**
     * Validate the form
     */
    protected function validateForm()
    {
        if ($this->frm->isSubmitted()) {
            $this->frm->cleanupFields();

            // validation
            $fields = $this->frm->getFields();

            foreach($this->languages as $language)
            {
                 $this->frm->getField('name_'. $language['abbreviation'])->isFilled(Language::getError('FieldIsRequired'));
            }

            if ($this->frm->isCorrect()) {
                $item['id'] = $this->id;

                $content = array();

                foreach($this->languages as $language)
                {
                    $specific['property_id'] = $item['id'];
                    $specific['language'] = $language['abbreviation'];
                    $specific['name'] = $this->frm->getField('name_'. $language['abbreviation'])->getValue();
                    $content[$language['abbreviation']] = $specific;

                     BackendSearchModel::saveIndex(
                        $this->getModule(), $item['id'],
                        array('name' => $specific['name']),
                        $language['abbreviation']
                    );
                }

                BackendShopProductPropertiesModel::update($item);
                BackendShopProductPropertiesModel::updateContent($content, $item['id'] );
                
                Model::triggerEvent(
                    $this->getModule(), 'after_edit', $item
                );
                $this->redirect(
                    Model::createURLForAction('Edit') . '&report=edited&id=' . $item['id']
                );
            }
        }
    }
}
