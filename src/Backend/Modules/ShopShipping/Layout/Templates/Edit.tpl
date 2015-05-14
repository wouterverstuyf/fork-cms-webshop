{include:{$BACKEND_CORE_PATH}/Layout/Templates/Head.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureStartModule.tpl}

<div class="pageTitle">
    <h2>{$lblShopShipping|ucfirst}: {$lblEdit}</h2>
</div>

{form:edit}
    <div class="box">
            <div class="heading">
                <h3>
                    <label for="country">{$lblDestination|ucfirst}</label>
                </h3>
            </div>
            <div class="options">
                {$ddmCountry} {$ddmCountryError}
            </div>
        </div>

        <div class="box">
            <div class="heading">
                <h3>
                    <label for="vatPct">{$lblPrice|ucfirst}</label>
                </h3>
            </div>
            <div class="options">
                <p>
                    <label for="vatPct">{$lblVatPct|ucfirst}</label>
                    {$txtVatPct} {$txtVatPctError}
                </p>
                 <p>
                    <label for="price">{$lblPrice|ucfirst}</label>
                    {$txtPrice} {$txtPriceError}
                </p>

                <ul class="inputList p0">
                    {iteration:price_is}
                    <li>
                        {$price_is.rbtPriceIs}
                        <label for="{$price_is.id}">{$price_is.label}</label>
                    </li>
                    {/iteration:price_is}
                </ul>

            </div>
        </div>
    
        <div class="box">
            <div class="heading">
                <h3>
                    {$lblAddVatFor|ucfirst}
                </h3>
            </div>
            <div class="options">
                <p><label for="addVatConsumer">{$chkAddVatConsumer} {$lblAddVatConsumer|ucfirst}</label></p>
                <p><label for="addVatCompany">{$chkAddVatCompany} {$lblAddVatCompany|ucfirst}</label></p>
            </div>
        </div>

         <div class="box">
            <div class="heading">
                <h3>{$lblStatus|ucfirst}</h3>
            </div>

            <div class="options">
                <ul class="inputList p0">
                    {iteration:hidden}
                    <li>
                        {$hidden.rbtHidden}
                        <label for="{$hidden.id}">{$hidden.label}</label>
                    </li>
                    {/iteration:hidden}
                </ul>
            </div>
        </div>


        <div class="box">
            <div class="heading">
                <h3>
                     <h3>{$chkHasDuration} <label for="hasDuration">{$lblShippingDuration|ucfirst}</label></h3>
                </h3>
            </div>
            <div class="options">
                {$txtDuration} {$txtDurationError}
                <span class="helpTxt">{$msgDurationInDays}</span>
            </div>
        </div>

        <div class="box">
            <div class="heading">
                <h3>{$chkHasFreeFrom} <label for="hasFreeFrom">{$lblFreeFrom|ucfirst}</label></h3>
            </div>
            <div class="options toggleNew toggle">
                <p>
                    <label for="freeFromPrice">{$lblPrice|ucfirst}</label>
                    {$txtFreeFromPrice} {$txtFreeFromPriceError}
                </p>
            </div>
        </div>

    <div class="fullwidthOptions">
        <a href="{$var|geturl:'delete'}&amp;id={$record.id}" data-message-id="confirmDelete" class="askConfirmation button linkButton icon iconDelete">
            <span>{$lblDelete|ucfirst}</span>
        </a>
        <div class="buttonHolderRight">
            <input id="addButton" class="inputButton button mainButton" type="submit" name="add" value="{$lblSave|ucfirst}" />
        </div>
    </div>

    <div id="confirmDelete" title="{$lblDelete|ucfirst}?" style="display: none;">
        <p>
            {$msgConfirmDelete|sprintf:{$record.country}}
        </p>
    </div>
{/form:edit}

{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureEndModule.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/Footer.tpl}
