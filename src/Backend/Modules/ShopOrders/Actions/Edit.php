<?php

namespace Backend\Modules\ShopOrders\Actions;

use Backend\Core\Engine\Base\ActionEdit;
use Backend\Core\Engine\Form;
use Backend\Core\Engine\Language;
use Backend\Core\Engine\Model;
use Backend\Modules\ShopOrders\Engine\Model as BackendShopOrdersModel;

use Frontend\Modules\ShopBase\Engine\Helper as FrontendShopOrdersHelper;
use Symfony\Component\Intl\Intl as Intl;

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
        if ($this->id == null || !BackendShopOrdersModel::exists($this->id)) {
            $this->redirect(
                Model::createURLForAction('Index') . '&error=non-existing'
            );
        }

        $this->record = BackendShopOrdersModel::get($this->id);
    }

    /**
     * Load the form
     */
    protected function loadForm()
    {
        // create form
        $this->frm = new Form('edit');

        $this->frm->addCheckbox('has_from_until', $this->record['from'] != NULL)->setAttribute('class', 'toggleDisable');
        $this->frm->addDate('from');
        $this->frm->addDate('until');

        $this->frm->addCheckbox('limit_use', $this->record['limit'] != NULL)->setAttribute('class', 'toggleDisable');
        $this->frm->addText('limit', $this->record['limit']);

        $this->frm->addText('name', $this->record['name']);
        $this->frm->addText('code', $this->record['code']);
        $this->frm->addText('discount', $this->record['discount']);

        $this->frm->addDropdown('discount_type', array(
                'value' => '€',
                'pct' => '%',
            ), $this->record['discount_type']);

    }

    /**
     * Parse the page
     */
    protected function parse()
    {
        parent::parse();

        $this->tpl->assign('record', $this->record);
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

            $fields['name']->isFilled(Language::err('FieldIsRequired'));
            $fields['code']->isFilled(Language::err('FieldIsRequired'));
            $fields['discount']->isFloat(Language::err('InvalidInteger'));

            if(BackendShopOrdersModel::existsByCode($fields['code']->getValue()) && $fields['code']->getValue() != $this->record['code']) {
                $fields['code']->setError(Language::err('CodeExists'));
            }

            if($fields['limit_use']->isChecked()) $fields['limit']->isInteger(Language::err('InvalidInteger'));
            if($fields['has_from_until']->isChecked()) {
                $this->frm->getField('from')->isValid(Language::err('DateIsInvalid'));
                $this->frm->getField('until')->isValid(Language::err('DateIsInvalid'));
            }


            if ($this->frm->isCorrect()) {
               
                $item['id'] = $this->id;
                $item['name'] = $fields['name']->getValue();
                $item['code'] = $fields['code']->getValue();
                $item['discount'] = $fields['discount']->getValue();
                $item['discount_type'] = $fields['discount_type']->getValue();

                $item['limit'] = NULL;
                if($fields['limit_use']->isChecked()) $item['limit'] = $fields['limit']->getValue();
                
                $item['from'] = NULL;
                $item['until'] = NULL;
                if($fields['has_from_until']->isChecked())
                {
                    $item['from'] = Model::getUTCDate('Y-m-d', Model::getUTCTimestamp($this->frm->getField('from')));
                    $item['until'] = Model::getUTCDate('Y-m-d', Model::getUTCTimestamp($this->frm->getField('until')));
                }

                BackendShopOrdersModel::update($item);

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
