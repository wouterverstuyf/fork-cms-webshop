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
        
        $this->frm->addText('duration');
        $this->frm->addText('price');
        $this->frm->addText('vat_pct');
        $this->frm->addCheckbox('add_vat_consumer');
        $this->frm->addCheckbox('add_vat_company');
        $this->frm->addText('free_from_price');

        // set hidden values
        $rbtHiddenValues[] = array('label' => Language::lbl('Hidden', $this->URL->getModule()), 'value' => 'Y');
        $rbtHiddenValues[] = array('label' => Language::lbl('Available'), 'value' => 'N');
        $this->frm->addRadiobutton('hidden', $rbtHiddenValues, 'N');

        $rbPriceVatValues[] = array('label' => Language::lbl('IsInclVat', $this->URL->getModule()), 'value' => 'Y');
        $rbPriceVatValues[] = array('label' => Language::lbl('IsExclVat'), 'value' => 'N');
        $this->frm->addRadiobutton('hidden', $rbPriceVatValues, 'N');

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
            $fields['price_vat']->isFilled(Language::err('FieldIsRequired'));

            $fields['price_vat']->isFloat(Language::err('InvalidInteger'));

            if($fields['free_from']->isFilled()) $fields['free_from']->isInteger(Language::err('InvalidInteger'));
            if($fields['duration']->isFilled()) $fields['duration']->isInteger(Language::err('InvalidInteger'));


            if ($this->frm->isCorrect()) {
                // build the item
                $item['country'] = $fields['country']->getValue();

                $item['free_from_price'] = 'N';
                if($fields['free_from_price']->isFilled()) $item['free_from_price'] = 'Y';
                $item['free_from_price'] = $fields['free_from_price']->getValue();

                $item['duration'] = $fields['duration']->getValue();
                $item['price_incl'] = $fields['price_incl']->getValue();
                $item['price_excl'] = $fields['price_excl']->getValue();
                $item['price_vat'] = $fields['price_vat']->getValue();
                $item['vat_pct'] = $fields['vat_pct']->getValue();
                $item['add_vat_consumer'] = $fields['add_vat_consumer']->getChecked() ? 'Y' : 'N';
                $item['add_vat_company'] = $fields['add_vat_company']->getChecked() ? 'Y' : 'N';


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
