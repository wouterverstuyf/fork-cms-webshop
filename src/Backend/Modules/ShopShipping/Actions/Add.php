<?php

namespace Backend\Modules\ShopShipping\Actions;

use Backend\Core\Engine\Base\ActionAdd;
use Backend\Core\Engine\Form;
use Backend\Core\Engine\Language;
use Backend\Core\Engine\Model;
use Backend\Modules\ShopShipping\Engine\Model as BackendShopShippingModel;

use Frontend\Modules\ShopBase\Engine\Helper as FrontendShopShippingHelper;
use Symfony\Component\Intl\Intl as Intl;

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

        $this->frm->addDropdown('country', Intl::getRegionBundle()->getCountryNames(Language::getInterfaceLanguage()), 'BE');
        
        $this->frm->addCheckbox('has_duration')->setAttribute('class', 'toggleDisable');
        $this->frm->addText('duration');
        $this->frm->addText('price', 10);
        $this->frm->addText('vat_pct', 21);
        $this->frm->addCheckbox('add_vat_consumer');
        $this->frm->addCheckbox('add_vat_company', true);
        $this->frm->addText('free_from_price', 0);
        $this->frm->addCheckbox('has_free_from')->setAttribute('class', 'toggleDisable');

        // set hidden values
        $rbtHiddenValues[] = array('label' => Language::lbl('NotAvailable', $this->URL->getModule()), 'value' => 'Y');
        $rbtHiddenValues[] = array('label' => Language::lbl('Available'), 'value' => 'N');
        $this->frm->addRadiobutton('hidden', $rbtHiddenValues, 'N');

        $rbtPriceIsValues[] = array('label' => Language::lbl('IsInclVat', $this->URL->getModule()), 'value' => 'Y');
        $rbtPriceIsValues[] = array('label' => Language::lbl('IsExclVat'), 'value' => 'N');
        $this->frm->addRadiobutton('price_is', $rbtPriceIsValues, 'Y');

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

            $fields['country']->isFilled(Language::err('FieldIsRequired'));

            if(BackendShopShippingModel::existsByCountry($fields['country']->getValue())) {
                $fields['country']->setError(Language::err('CountryExists'));
            }

            $fields['vat_pct']->isFloat(Language::err('InvalidInteger'));
            $fields['price']->isFloat(Language::err('InvalidInteger'));

            if($fields['has_duration']->isChecked()) $fields['duration']->isInteger(Language::err('InvalidInteger'));
            if($fields['has_free_from']->isChecked()) $fields['free_from_price']->isInteger(Language::err('InvalidInteger'));

            if ($this->frm->isCorrect()) {
               
                // build the item
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


                // insert it
                $item['id'] = BackendShopShippingModel::insert($item);

                Model::triggerEvent(
                    $this->getModule(), 'after_add', $item
                );
                $this->redirect(
                    Model::createURLForAction('Index') . '&report=added&highlight=row-' . $item['id']
                );
            }
        }
    }
}
