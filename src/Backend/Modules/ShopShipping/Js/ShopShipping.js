/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

/**
 * Interaction for the Shop Shipping module
 *
 * @author Frederik Heyninck <frederik@figure8.be>
 */
jsBackend.shop_shipping =
{
    // constructor
    init: function()
    {
    	// disabled fields
		$('.toggleDisable').change(function(e) 
		{
			var reverse = false;
			if($(e.target).hasClass('reverseToggle')) reverse = true;

			if(
				(reverse && !$(e.target).is(':checked')) ||
				(!reverse && $(e.target).is(':checked'))
			){
				$(e.target).closest('.box').find('input.inputText').each(function(i, el){
					if(!$(el).hasClass('alwaysDisabled')) $(el).removeClass('disabled').removeAttr('disabled')
				});
			}
			else
			{
				$(e.target).closest('.box').find('input.inputText').each(function(i, el){
					if(!$(el).hasClass('alwaysDisabled')) $(el).addClass('disabled').attr('disabled', 'disabled');
				});
			}
		}).trigger('change');
    }
}

$(jsBackend.shop_shipping.init);
