<?php

namespace Backend\Modules\ShopDiscountCodes\Actions;

use Backend\Core\Engine\Base\ActionAdd;
use Backend\Core\Engine\Form;
use Backend\Core\Engine\Language;
use Backend\Core\Engine\Model;
use Backend\Modules\ShopDiscountCodes\Engine\Model as BackendShopDiscountCodesModel;


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
        
        $this->frm->addCheckbox('has_from_until')->setAttribute('class', 'toggleDisable');
        $this->frm->addDate('from');
        $this->frm->addDate('until');

        $this->frm->addCheckbox('limit_use')->setAttribute('class', 'toggleDisable');
        $this->frm->addText('limit');

        $this->frm->addText('name');
        $this->frm->addText('code', mb_substr(strrev(uniqid()), 0,7));
        $this->frm->addText('discount');

        $this->frm->addDropdown('discount_type', array(
                'value' => '€',
                'pct' => '%',
            ), 'pct');
    }

    /**
     * Parse the page
     */
    protected function parse()
    {
        parent::parse();
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

            if(BackendShopDiscountCodesModel::existsByCode($fields['code']->getValue())) {
                $fields['code']->setError(Language::err('Codexists'));
            }

            if($fields['limit_use']->isChecked()) $fields['limit']->isInteger(Language::err('InvalidInteger'));
            if($fields['has_from_until']->isChecked()) {
                $this->frm->getField('from')->isValid(Language::err('DateIsInvalid'));
                $this->frm->getField('until')->isValid(Language::err('DateIsInvalid'));
            }

            if ($this->frm->isCorrect()) {
               
                // build the item
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
              
                // insert it
                $item['id'] = BackendShopDiscountCodesModel::insert($item);

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
