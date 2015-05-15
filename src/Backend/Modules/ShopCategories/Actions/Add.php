<?php

namespace Backend\Modules\ShopCategories\Actions;

use Backend\Core\Engine\Base\ActionAdd;
use Backend\Core\Engine\Form;
use Backend\Core\Engine\Language;
use Backend\Core\Engine\Model;
use Backend\Modules\ShopCategories\Engine\Model as BackendShopCategoriesModel;
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

        $this->frm->addImage('image');

        // set hidden values
        $rbtHiddenValues[] = array('label' => Language::lbl('Hidden', $this->URL->getModule()), 'value' => 'Y');
        $rbtHiddenValues[] = array('label' => Language::lbl('Published'), 'value' => 'N');

        $this->frm->addRadiobutton('hidden', $rbtHiddenValues, 'N');

        foreach($this->languages as &$language)
        {
            $language['formElements']['txtName'] = $this->frm->addText('name_'. $language['abbreviation'], isset($this->record['content'][$language['abbreviation']]['name']) ? $this->record['content'][$language['abbreviation']]['name'] : '', null, 'inputText title');
            $language['formElements']['txtDescription'] = $this->frm->addEditor('description_'. $language['abbreviation'], isset($this->record['content'][$language['abbreviation']]['description']) ? $this->record['content'][$language['abbreviation']]['description'] : '');

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

            ShopHelper::validateImage($this->frm, 'image');

            foreach($this->languages as $language)
            {
                 $this->frm->getField('name_'. $language['abbreviation'])->isFilled(Language::getError('FieldIsRequired'));
            }

            if ($this->frm->isCorrect()) {

                $item['hidden'] = $fields['hidden']->getValue();

                $imagePath = ShopHelper::generateFolders($this->getModule());

                // image provided?
                if ($fields['image']->isFilled()) {
                    // build the image name
                    $item['image'] = uniqid() . '.' . $fields['image']->getExtension();

                    // upload the image & generate thumbnails
                    $fields['image']->generateThumbnails($imagePath, $item['image']);
                }

                $item['id'] = BackendShopCategoriesModel::insert($item);

                $content = array();

                foreach($this->languages as $language)
                {
                    $specific['Category_id'] = $item['id'];
                    $specific['language'] = $language['abbreviation'];
                    $specific['name'] = $this->frm->getField('name_'. $language['abbreviation'])->getValue();
                    $specific['description'] = ($this->frm->getField('description_'. $language['abbreviation'])->isFilled()) ? $this->frm->getField('description_'. $language['abbreviation'])->getValue() : null;
                    $content[$language['abbreviation']] = $specific;

                     BackendSearchModel::saveIndex(
                        $this->getModule(), $item['id'],
                        array('name' => $specific['name'], 'description' => $specific['description']),
                        $language['abbreviation']
                    );
                }

                BackendShopCategoriesModel::insertTreeNode($item['id'], 2);

                // insert it
               BackendShopCategoriesModel::insertContent($content);

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
