{include:{$BACKEND_CORE_PATH}/Layout/Templates/Head.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureStartModule.tpl}

<div class="pageTitle">
    <h2>
        {$lblShopCategories|ucfirst}
    </h2>
    <div class="buttonHolderRight">
        <a href="{$var|geturl:'add'}" class="button icon iconAdd" title="{$lblAddShopCategories|ucfirst}">
            <span>{$lblAddShopCategories|ucfirst}</span>
        </a>
    </div>
</div>

{option:tree}
    <div class="wizard">
        <ul>
            {iteration:tree}
                {option:!tree.last}
                    <li class="{option:tree.selected}selected{/option:tree.selected}">
                        <a href="{$var|geturl:'Index'}{option:!tree.child_of}&amp;child_of={$tree.id}{/option:!tree.child_of}"><b>{$tree.name}</b></a>
                    </li>
                {/option:!tree.last}
            {/iteration:tree}
        </ul>
    </div>
{/option:tree}

{option:dataGrid}
    <div class="dataGridHolder">
        {$dataGrid}
    </div>
{/option:dataGrid}

{option:!dataGrid}
    {$msgNoItems}
{/option:!dataGrid}

{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureEndModule.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/Footer.tpl}
