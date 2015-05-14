<?php

namespace Backend\Modules\ShopShipping\Actions;

use Backend\Core\Engine\Base\ActionEdit;
use Backend\Core\Engine\Form;
use Backend\Core\Engine\Language;
use Backend\Core\Engine\Model;
use Backend\Modules\ShopShipping\Engine\Model as BackendShopShippingModel;

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

        $this->frm->addText('free_from' ,$this->record['free_from'], null, 'inputText title', 'inputTextError title');

        // build array with options for the destination Dropdown
        $DropdownDestinationValues = array(Language::lbl('A'));
        $this->frm->addDropdown('destination', $DropdownDestinationValues, $this->record['destination'])->setDefaultElement('');
        $this->frm->addText('duration_estimate', $this->record['duration_estimate']);
        $this->frm->addText('price_incl', $this->record['price_incl']);
        $this->frm->addText('price_excl', $this->record['price_excl']);
        $this->frm->addText('price_vat', $this->record['price_vat']);
        $this->frm->addText('vat_pct', $this->record['vat_pct']);
        $this->frm->addCheckbox('add_vat_consumer', $this->record['add_vat_consumer'] == 'Y');
        $this->frm->addCheckbox('add_vat_company', $this->record['add_vat_company'] == 'Y');

        // meta
        $this->meta = new Meta($this->frm, $this->record['meta_id'], 'free_from', true);
        $this->meta->setUrlCallBack('Backend\Modules\ShopShipping\Engine\Model', 'getUrl', array($this->record['id']));

    }

    /**
     * Parse the page
     */
    protected function parse()
    {
        parent::parse();

        // get url
        $url = Model::getURLForBlock($this->URL->getModule(), 'Detail');
        $url404 = Model::getURL(404);

        // parse additional variables
        if ($url404 != $url) {
            $this->tpl->assign('detailURL', SITE_URL . $url);
        }
        $this->record['url'] = $this->meta->getURL();


        $this->tpl->assign('item', $this->record);
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

            $fields['free_from']->isFilled(Language::err('FieldIsRequired'));

            // validate meta
            $this->meta->validate();

            if ($this->frm->isCorrect()) {
                $item['id'] = $this->id;
                $item['language'] = Language::getWorkingLanguage();

                $item['destination'] = $fields['destination']->getValue();
                $item['free_from'] = $fields['free_from']->getValue();
                $item['duration_estimate'] = $fields['duration_estimate']->getValue();
                $item['price_incl'] = $fields['price_incl']->getValue();
                $item['price_excl'] = $fields['price_excl']->getValue();
                $item['price_vat'] = $fields['price_vat']->getValue();
                $item['vat_pct'] = $fields['vat_pct']->getValue();
                $item['add_vat_consumer'] = $fields['add_vat_consumer']->getChecked() ? 'Y' : 'N';
                $item['add_vat_company'] = $fields['add_vat_company']->getChecked() ? 'Y' : 'N';

                $item['meta_id'] = $this->meta->save();

                BackendShopShippingModel::update($item);
                $item['id'] = $this->id;

                Model::triggerEvent(
                    $this->getModule(), 'after_edit', $item
                );
                $this->redirect(
                    Model::createURLForAction('Index') . '&report=edited&highlight=row-' . $item['id']
                );
            }
        }
    }
}
