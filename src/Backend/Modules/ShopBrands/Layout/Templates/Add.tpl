{include:{$BACKEND_CORE_PATH}/Layout/Templates/Head.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureStartModule.tpl}

<div class="pageTitle">
    <h2>{$lblShopBrands|ucfirst}: {$lblAdd}</h2>
</div>

{form:add}

    <table border="0" cellspacing="0" cellpadding="0" width="100%">
        <tr>
            <td id="leftColumn">

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

                            <div class="box">
                                <div class="heading">
                                    <h3>{$lblDescription|ucfirst}</h3>
                                </div>
                                <div class="optionsRTE">
                                    {$languages.txtDescription} {$languages.txtDescriptionError}
                                </div>
                            </div>

                        </div>
                    {/iteration:languages}
                 </div>
             </td>

             <td id="sidebar">

                <div class="box">
                    <div class="heading">
                        <h3>
                            <label for="image">{$lblImage|ucfirst}</label>
                        </h3>
                    </div>
                    <div class="options">
                        {$fileImage} {$fileImageError}
                    </div>
                </div>

                <div class="box">
                    <div class="heading">
                        <h3>
                            <label for="website">{$lblWebsite|ucfirst}</label>
                        </h3>
                    </div>
                    <div class="options">
                        {$txtWebsite} {$txtWebsiteError}
                        <span class="helpTxt">{$msgHelpWebsite}</span>
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

            </td>
        </tr>
    </table>

    <div class="fullwidthOptions">
        <div class="buttonHolderRight">
            <input id="addButton" class="inputButton button mainButton" type="submit" name="add" value="{$lblAdd|ucfirst}" />
        </div>
    </div>
{/form:add}

{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureEndModule.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/Footer.tpl}
