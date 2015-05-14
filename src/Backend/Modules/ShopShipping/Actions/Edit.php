<?php

namespace Backend\Modules\ShopShipping\Actions;

use Backend\Core\Engine\Base\ActionEdit;
use Backend\Core\Engine\Form;
use Backend\Core\Engine\Language;
use Backend\Core\Engine\Model;
use Backend\Modules\ShopShipping\Engine\Model as BackendShopShippingModel;

use Frontend\Modules\ShopBase\Engine\Helper as FrontendShopShippingHelper;
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
        if ($this->id == null || !BackendShopShippingModel::exists($this->id)) {
            $this->redirect(
                Model::createURLForAction('Index') . '&error=non-existing'
            );
        }

        $this->record = BackendShopShippingModel::get($this->id);
    }

    /**
     * Load the form
     */
    protected function loadForm()
    {
        // create form
        $this->frm = new Form('edit');

        $price = $this->record['price_excl'];
        if($this->record['price_is_incl_vat'] == 'Y') $price = $this->record['price_incl'];

        $this->frm->addDropdown('country', Intl::getRegionBundle()->getCountryNames(Language::getInterfaceLanguage()), $this->record['country']);
        
        $this->frm->addCheckbox('has_duration', ($this->record['duration'] != NULL))->setAttribute('class', 'toggleDisable');
        $this->frm->addText('duration', $this->record['duration']);
        $this->frm->addText('price', (float) $price);
        $this->frm->addText('vat_pct', (float) $this->record['vat_pct']);
        $this->frm->addCheckbox('add_vat_consumer', $this->record['add_vat_consumer'] == 'Y');
        $this->frm->addCheckbox('add_vat_company', $this->record['add_vat_company'] == 'Y');
        $this->frm->addText('free_from_price', (float) $this->record['free_from_price']);
        $this->frm->addCheckbox('has_free_from', ($this->record['free_from_price'] != NULL))->setAttribute('class', 'toggleDisable');

        // set hidden values
        $rbtHiddenValues[] = array('label' => Language::lbl('NotAvailable', $this->URL->getModule()), 'value' => 'Y');
        $rbtHiddenValues[] = array('label' => Language::lbl('Available'), 'value' => 'N');
        $this->frm->addRadiobutton('hidden', $rbtHiddenValues, $this->record['hidden']);

        $rbtPriceIsValues[] = array('label' => Language::lbl('IsInclVat', $this->URL->getModule()), 'value' => 'Y');
        $rbtPriceIsValues[] = array('label' => Language::lbl('IsExclVat'), 'value' => 'N');
        $this->frm->addRadiobutton('price_is', $rbtPriceIsValues, $this->record['price_is_incl_vat']);

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

            $fields['country']->isFilled(Language::err('FieldIsRequired'));

            if(BackendShopShippingModel::existsByCountry($fields['country']->getValue()) && $fields['country']->getValue() != $this->record['country']) {
                $fields['country']->setError(Language::err('CountryExists'));
            }

            $fields['vat_pct']->isFloat(Language::err('InvalidInteger'));
            $fields['price']->isFloat(Language::err('InvalidInteger'));

            if($fields['has_duration']->isChecked()) $fields['duration']->isInteger(Language::err('InvalidInteger'));
            if($fields['has_free_from']->isChecked()) $fields['free_from_price']->isInteger(Language::err('InvalidInteger'));


            if ($this->frm->isCorrect()) {
               
                $item['id'] = $this->id;
                $item['country'] = $fields['country']->getValue();

                $item['add_vat_consumer'] = $fields['add_vat_consumer']->getChecked() ? 'Y' : 'N';
                $item['add_vat_company'] = $fields['add_vat_company']->getChecked() ? 'Y' : 'N';


                $item['free_from_price'] = NULL;
                if($fields['has_free_from']->isChecked()) $item['free_from_price'] = $fields['free_from_price']->getValue();
                
                $item['duration'] = NULL;
                if($fields['has_duration']->isChecked()) $item['duration'] = $fields['duration']->getValue();
                
                $item['vat_pct'] = $fields['vat_pct']->getValue();

                $item['hidden'] = $fields['hidden']->getValue();

                $item['price_is_incl_vat'] = $fields['price_is']->getValue();

                // is incl vat
                if($fields['price_is']->getValue() == 'Y') {
                    $item['price_incl'] = $fields['price']->getValue();
                    $item['price_excl'] = FrontendShopShippingHelper::calculatePriceExclVat($item['price_incl'], $item['vat_pct']);
                    $item['price_vat'] = FrontendShopShippingHelper::calculatePriceVat($item['price_excl'], $item['vat_pct']);
                } else {
                     $item['price_excl'] = $fields['price']->getValue();
                     $item['price_incl'] = FrontendShopShippingHelper::calculatePriceInclVat($item['price_excl'], $item['vat_pct']);
                     $item['price_vat'] = FrontendShopShippingHelper::calculatePriceVat($item['price_excl'], $item['vat_pct']);
                }

                BackendShopShippingModel::update($item);
                $item['id'] = $this->id;

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
