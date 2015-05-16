{include:{$BACKEND_CORE_PATH}/Layout/Templates/Head.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureStartModule.tpl}

<div class="pageTitle">
    <h2>{$lblShopProductProperties|ucfirst}: {$lblEdit}</h2>
    <div class="buttonHolderRight">
        <a href="{$var|geturl:'add_value'}&amp;id={$record.id}"  class=" button  icon iconAdd">
            <span>{$lblAddValue|ucfirst}</span>
        </a>
    </div>
</div>

{form:edit}
  
     <div class="tabs">
        <ul>
            <li><a href="#tabValues">{$lblValues|ucfirst}</a></li>
           {iteration:languages}<li><a href="#tab{$languages.abbreviation|uppercase}">{$languages.label|ucfirst}</a></li>{/iteration:languages}
        </ul>

        <div id="tabValues">

            {option:dataGrid}
            <div class="dataGridHolder">
                {$dataGrid}
            </div>
            {/option:dataGrid}

            {option:!dataGrid}
                {$msgNoValues}
            {/option:!dataGrid}
           
        </div>

        {iteration:languages}
            <div id="tab{$languages.abbreviation|uppercase}">

                <p>
                    <label for="name{$languages.abbreviation|ucfirst}">{$lblName|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
                    {$languages.txtName} {$languages.txtNameError}
                </p>

            </div>
        {/iteration:languages}


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
            {$msgConfirmDelete|sprintf:'brand'}
        </p>
    </div>
{/form:edit}

{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureEndModule.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/Footer.tpl}
