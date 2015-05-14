<?php

namespace Backend\Modules\ShopCategories\Actions;

use Backend\Core\Engine\Base\ActionEdit;
use Backend\Core\Engine\Form;
use Backend\Core\Engine\Language;
use Backend\Core\Engine\Model;
use Backend\Modules\ShopCategories\Engine\Model as BackendShopCategoriesModel;
use Backend\Modules\Search\Engine\Model as BackendSearchModel;

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
        if ($this->id == null || !BackendShopCategoriesModel::exists($this->id)) {
            $this->redirect(
                Model::createURLForAction('Index') . '&error=non-existing'
            );
        }

        $this->record = BackendShopCategoriesModel::get($this->id);

       // \Spoon::dump($this->record);
    }

    /**
     * Load the form
     */
    protected function loadForm()
    {
        // create form
        $this->frm = new Form('edit');

        $this->frm->addImage('image');
        $this->frm->addCheckbox('delete_image');

        // set hidden values
        $rbtHiddenValues[] = array('label' => Language::lbl('Hidden', $this->URL->getModule()), 'value' => 'Y');
        $rbtHiddenValues[] = array('label' => Language::lbl('Published'), 'value' => 'N');

        $this->frm->addRadiobutton('hidden', $rbtHiddenValues, $this->record['hidden']);

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
        $this->tpl->assign('record', $this->record);
        $this->tpl->assign('imageUrl', ShopHelper::getImageUrl($this->record['image'], $this->getModule()));
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
                $item['id'] = $this->id;

                $item['hidden'] = $fields['hidden']->getValue();

                $imagePath = ShopHelper::generateFolders($this->getModule());

                if($fields['delete_image']->isChecked()){
                    $item['image'] = NULL;
                    Model::deleteThumbnails(FRONTEND_FILES_PATH . '/' . $this->getModule() . '/image',  $this->record['image']);
                }

                // image provided?
                if ($fields['image']->isFilled()) {
                    // build the image name
                    $item['image'] = uniqid() . '.' . $fields['image']->getExtension();

                    // upload the image & generate thumbnails
                    $fields['image']->generateThumbnails($imagePath, $item['image']);
                }

                $content = array();

                foreach($this->languages as $language)
                {
                    $specific['category_id'] = $item['id'];
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

                BackendShopCategoriesModel::update($item);
                BackendShopCategoriesModel::updateContent($content, $item['id'] );
                
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
