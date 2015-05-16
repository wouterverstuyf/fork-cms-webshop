<?php

namespace Backend\Modules\ShopProductProperties\Actions;

use Backend\Core\Engine\Base\ActionAdd;
use Backend\Core\Engine\Form;
use Backend\Core\Engine\Language;
use Backend\Core\Engine\Model;
use Backend\Modules\ShopProductProperties\Engine\Model as BackendShopProductPropertiesModel;
use Backend\Modules\Search\Engine\Model as BackendSearchModel;

use Backend\Modules\ShopBase\Engine\Helper as ShopHelper;

/**
 * This is the add-action, it will display a form to create a new item
 *
 * @author Frederik Heyninck <frederik@figure8.be>
 */
class Add extends ActionAdd
{
    /**
     * Execute the actions
     */
    public function execute()
    {
        parent::execute();

        $this->languages = ShopHelper::getLanguages();

        $this->loadForm();
        $this->validateForm();

        $this->parse();
        $this->display();
    }

    /**
     * Load the form
     */
    protected function loadForm()
    {
        $this->frm = new Form('add');

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
                
                $item = array();
                $item['id'] = BackendShopProductPropertiesModel::insert($item);

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

                // insert it
               BackendShopProductPropertiesModel::insertContent($content);

                Model::triggerEvent(
                    $this->getModule(), 'after_add', $item
                );
                $this->redirect(
                    Model::createURLForAction('Edit') . '&report=added&id=' . $item['id']
                );
            }
        }
    }
}
