{*
* 2014-2015 (c) Axalone France - Express-Mailing
*
* This file is a commercial module for Prestashop
* Do not edit or add to this file if you wish to upgrade PrestaShop or
* customize PrestaShop for your needs please refer to
* http://www.express-mailing.com for more information.
*
* @author    Axalone France <info@express-mailing.com>
* @copyright 2014-2015 (c) Axalone France
* @license   http://opensource.org/licenses/GPL-3.0  GNU General Public License, version 3 (GPL-3.0)
*}

{if isset($smarty_fax_tickets) && is_array($smarty_fax_tickets)}
	<table>
		{foreach $smarty_fax_tickets as $key => $ticket}
		<tr><td class="radio" style="padding-bottom: 2px; border-bottom: medium dotted #C5E9F3{if $key == 0}; border-top: medium dotted #C5E9F3{/if}">
			<label>
				<input type="radio" name="product" value="{$ticket.product_ref|unescape}" {if ($cart_product == '' and $key == 0) or ($cart_product == $ticket.product_ref) }checked="checked" {/if}/>
				<span style="width:170px; display:inline-block">{$ticket.product_units|number_format:0:",":"."} {$fax_credits}<br>{$ticket.product_desc|escape}</span>
				<span style="width:170px; display:inline-block"><b>{$ticket.normal_price|number_format:2:",":"."} {$euro_symbol}</b><br>{$fax_per_unit|sprintf:($ticket.normal_price / $ticket.product_units)}</span>
			</label>
		</td></tr>
		{/foreach}
	</table>
{/if}

{if isset($smarty_sms_tickets) && is_array($smarty_sms_tickets)}
	<table>
		{foreach $smarty_sms_tickets as $key => $ticket}
		<tr><td class="radio" style="padding-bottom: 2px; border-bottom: medium dotted #C5E9F3{if $key == 0}; border-top: medium dotted #C5E9F3{/if}">
			<label>
				<input type="radio" name="product" value="{$ticket.product_ref|unescape}" {if ($cart_product == '' and $key == 0) or ($cart_product == $ticket.product_ref) }checked="checked" {/if}/>
				<span style="width:170px; display:inline-block">{$ticket.product_units|number_format:0:",":"."} {$sms_credits}<br>{$ticket.product_desc|escape}</span>
				<span style="width:170px; display:inline-block"><b>{$ticket.normal_price|number_format:2:",":"."} {$euro_symbol}</b><br>{$sms_per_unit|sprintf:($ticket.normal_price / $ticket.product_units)}</span>
			</label>
		</td></tr>
		{/foreach}
	</table>
{/if}