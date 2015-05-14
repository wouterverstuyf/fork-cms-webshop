{include:{$BACKEND_CORE_PATH}/Layout/Templates/Head.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureStartModule.tpl}

<div class="pageTitle">
    <h2>{$lblShopShipping|ucfirst}: {$lblEdit}</h2>
</div>

{form:edit}
    <label for="freeFrom">{$lblFreeFrom|ucfirst}</label>
    {$txtFreeFrom} {$txtFreeFromError}

    <div id="pageUrl">
        <div class="oneLiner">
            {option:detailURL}<p><span><a href="{$detailURL}/{$item.url}">{$detailURL}/<span id="generatedUrl">{$item.url}</span></a></span></p>{/option:detailURL}
            {option:!detailURL}<p class="infoMessage">{$errNoModuleLinked}</p>{/option:!detailURL}
        </div>
    </div>


    <div class="tabs">
        <ul>
            <li><a href="#tabContent">{$lblContent|ucfirst}</a></li>
            <li><a href="#tabSEO">{$lblSEO|ucfirst}</a></li>
        </ul>

        <div id="tabContent">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
                <tr>
                    <td id="leftColumn">

                        <div class="box">
                            <div class="heading">
                                <h3>
                                    <label for="durationEstimate">{$lblDurationEstimate|ucfirst}</label>
                                </h3>
                            </div>
                            <div class="options">
                                {$txtDurationEstimate} {$txtDurationEstimateError}
                            </div>
                        </div>

                        <div class="box">
                            <div class="heading">
                                <h3>
                                    <label for="priceIncl">{$lblPriceIncl|ucfirst}</label>
                                </h3>
                            </div>
                            <div class="options">
                                {$txtPriceIncl} {$txtPriceInclError}
                            </div>
                        </div>

                        <div class="box">
                            <div class="heading">
                                <h3>
                                    <label for="priceExcl">{$lblPriceExcl|ucfirst}</label>
                                </h3>
                            </div>
                            <div class="options">
                                {$txtPriceExcl} {$txtPriceExclError}
                            </div>
                        </div>

                        <div class="box">
                            <div class="heading">
                                <h3>
                                    <label for="priceVat">{$lblPriceVat|ucfirst}</label>
                                </h3>
                            </div>
                            <div class="options">
                                {$txtPriceVat} {$txtPriceVatError}
                            </div>
                        </div>

                        <div class="box">
                            <div class="heading">
                                <h3>
                                    <label for="vatPct">{$lblVatPct|ucfirst}</label>
                                </h3>
                            </div>
                            <div class="options">
                                {$txtVatPct} {$txtVatPctError}
                            </div>
                        </div>


                    </td>

                    <td id="sidebar">

                            <div class="box">
                                <div class="heading">
                                    <h3>
                                        <label for="destination">{$lblDestination|ucfirst}</label>
                                    </h3>
                                </div>
                                <div class="options">
                                    {$ddmDestination} {$ddmDestinationError}
                                </div>
                            </div>

                            <div class="box">
                                <div class="heading">
                                    <h3>
                                        {$lblAddVatConsumer|ucfirst}
                                    </h3>
                                </div>
                                <div class="options">
                                    {$chkAddVatConsumer} <label for="addVatConsumer">{$lblAddVatConsumer|ucfirst} </label> {$chkAddVatConsumerError}
                                </div>
                            </div>

                            <div class="box">
                                <div class="heading">
                                    <h3>
                                        {$lblAddVatCompany|ucfirst}
                                    </h3>
                                </div>
                                <div class="options">
                                    {$chkAddVatCompany} <label for="addVatCompany">{$lblAddVatCompany|ucfirst} </label> {$chkAddVatCompanyError}
                                </div>
                            </div>


                    </td>
                </tr>
            </table>
        </div>

        <div id="tabSEO">
            {include:{$BACKEND_CORE_PATH}/Layout/Templates/Seo.tpl}
        </div>

    </div>

    <div class="fullwidthOptions">
        <a href="{$var|geturl:'delete'}&amp;id={$item.id}" data-message-id="confirmDelete" class="askConfirmation button linkButton icon iconDelete">
            <span>{$lblDelete|ucfirst}</span>
        </a>
        <div class="buttonHolderRight">
            <input id="addButton" class="inputButton button mainButton" type="submit" name="add" value="{$lblSave|ucfirst}" />
        </div>
    </div>

    <div id="confirmDelete" title="{$lblDelete|ucfirst}?" style="display: none;">
        <p>
            {$msgConfirmDelete|sprintf:{$item.title}}
        </p>
    </div>
{/form:edit}

{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureEndModule.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/Footer.tpl}
