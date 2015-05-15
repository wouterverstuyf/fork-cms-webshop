{include:{$BACKEND_CORE_PATH}/Layout/Templates/Head.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureStartModule.tpl}

<div class="pageTitle">
    <h2>{$lblShopDiscountCodes|ucfirst}: {$lblEdit}</h2>
</div>

{form:edit}
    <table border="0" cellspacing="0" cellpadding="0" width="100%">
        <tr>
            <td id="leftColumn">

                <div class="box">
                    <div class="heading">
                        <h3>
                            <label for="vatPct">{$lblDetails|ucfirst}</label>
                        </h3>
                    </div>
                    <div class="options">
                        <p>
                            <label for="name">{$lblName|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
                            {$txtName} {$txtNameError}
                        </p>
                         <p>
                            <label for="discount">{$lblDiscount|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
                            {$txtDiscount} {$ddmDiscountType}
                            {$txtDiscountError}
                        </p>

                       <p>
                            <label for="code">{$lblCode|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
                            {$txtCode} {$txtCodeError}
                        </p>

                    </div>
                </div>

             </td>

             <td id="sidebar">
                 <div class="box">
                    <div class="heading">
                        <h3>
                             <h3>{$chkLimitUse} <label for="limitUse">{$lblLimitUsage|ucfirst}</label></h3>
                        </h3>
                    </div>
                    <div class="options">
                        {$txtLimit} {$txtLimitError}
                        <span class="helpTxt">{$msgHelpLimit}</span>
                    </div>
                </div>

                <div class="box">
                    <div class="heading">
                        <h3>{$chkHasFromUntil} <label for="hasFromUntil">{$lblLimitByDates|ucfirst}</label></h3>
                    </div>
                    <div class="options toggleNew toggle">
                        <p>
                            <label for="from">{$lblFrom|ucfirst}</label>
                            {$txtFrom} {$txtFromError}
                        </p>
                         <p>
                            <label for="until">{$lblUntil|ucfirst}</label>
                            {$txtUntil} {$txtUntilError}
                        </p>
                    </div>
                </div>
              </td>
        </tr>
    </table>

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
            {$msgConfirmDelete|sprintf:{$record.name}}
        </p>
    </div>
{/form:edit}

{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureEndModule.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/Footer.tpl}
