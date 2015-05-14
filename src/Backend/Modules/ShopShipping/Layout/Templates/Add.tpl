{include:{$BACKEND_CORE_PATH}/Layout/Templates/Head.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureStartModule.tpl}

<div class="pageTitle">
    <h2>{$lblShopShipping|ucfirst}: {$lblAdd}</h2>
</div>

{form:add}
   


    
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
    

    <div class="fullwidthOptions">
        <div class="buttonHolderRight">
            <input id="addButton" class="inputButton button mainButton" type="submit" name="add" value="{$lblPublish|ucfirst}" />
        </div>
    </div>
{/form:add}

{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureEndModule.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/Footer.tpl}
