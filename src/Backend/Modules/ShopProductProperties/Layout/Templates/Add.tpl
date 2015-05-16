{include:{$BACKEND_CORE_PATH}/Layout/Templates/Head.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureStartModule.tpl}

<div class="pageTitle">
    <h2>{$lblShopProductProperties|ucfirst}: {$lblAdd}</h2>
</div>

{form:add}

    
     <div class="tabs">
        <ul>
           {iteration:languages}<li><a href="#tab{$languages.abbreviation|uppercase}">{$languages.label|ucfirst}</a></li>{/iteration:languages}
        </ul>

        {iteration:languages}
            <div id="tab{$languages.abbreviation|uppercase}">

                <p>
                    <label for="name{$languages.abbreviation|ucfirst}">{$lblName|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
                    {$languages.txtName} {$languages.txtNameError}
                </p>

                 <div class="generalMessage infoMessage content">
                    <p class="p0">
                        {$msgAfterAddingThisPropertyYouCanAddTheValues}
                    </p>
                </div>

            </div>
        {/iteration:languages}
     </div>

    

    <div class="fullwidthOptions">
        <div class="buttonHolderRight">
            <input id="addButton" class="inputButton button mainButton" type="submit" name="add" value="{$lblAdd|ucfirst}" />
        </div>
    </div>
{/form:add}

{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureEndModule.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/Footer.tpl}
