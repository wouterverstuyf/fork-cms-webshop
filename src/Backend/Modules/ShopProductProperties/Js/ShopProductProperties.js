/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

/**
 * Interaction for the ShopProductProperties module
 *
 * @author Frederik Heyninck <frederik@figure8.be>
 */
jsBackend.shop_product_properties =
{
    // constructor
    init: function()
    {
    	if($('.dataGrid td.translationValue').length > 0)
		{
			// bind
			$('.dataGrid td.translationValue').inlineTextEdit(
			{
				params: { fork: { action: 'SaveValue' } },
				tooltip: jsBackend.locale.msg('ClickToEdit'),
				afterSave: function(item)
				{
					if(item.find('span:empty').length == 1) item.addClass('highlighted');
					else item.removeClass('highlighted');
				}
			});

			// highlight all empty items
			$('.dataGrid td.translationValue span:empty').parents('td.translationValue').addClass('highlighted');
		}
    }
}

$(jsBackend.shop_product_properties.init);
