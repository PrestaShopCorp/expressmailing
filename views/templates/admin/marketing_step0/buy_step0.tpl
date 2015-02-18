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

{if is_array($smarty_fax_tickets) || is_array($smarty_sms_tickets)}
<form id="buy_form" class="defaultForm form-horizontal adminmarketingx" action="index.php?controller=AdminMarketingBuy&token={Tools::getAdminTokenLite('AdminMarketingBuy')|escape}" method="post" enctype="multipart/form-data" novalidate>
	<div class="panel" id="fieldset_0">
		<div class="panel-heading">
			<i class="icon-shopping-cart"></i>&nbsp;{l s='Buy credit packs' mod='expressmailing'}
		</div>
		<div class="form-wrapper">
			<div class="form-group">
				{if is_array($smarty_fax_tickets)}
					<div class="col-lg-6 ">
						{foreach $smarty_fax_tickets as $ticket}
							{if isset($ticket.promo_ending)}<font color="red" style="display: block">{l s='Promotion until' mod='expressmailing'} {$tool_date->getLocalizableDate($ticket.promo_ending)}</font>{/if}
							{if isset($ticket.promo_desc) and !empty($ticket.promo_desc)}<font color="red" style="display: block"><b>{$ticket.promo_desc|escape}</b></font>{/if}
							<div class="radio" {if isset($ticket.promo_ending)}style="margin-bottom: 8px"{/if}>
								<label>
									<input type="radio" name="product" value="{$ticket.product_ref|unescape}" />
									<span style="width:170px; display:inline-block">
										{if isset($ticket.promo_units) and ($ticket.promo_units > 0)}
											<strike>{$ticket.product_units|number_format:0:",":"."} {l s='fax credits' mod='expressmailing'}</strike>
											<br><font color="red"><b>{$ticket.promo_units|number_format:0:",":"."} {l s='fax credits' mod='expressmailing'}</b></font><br>{$ticket.product_desc|escape}
											{$ticket.product_units = $ticket.promo_units}
										{else}
											{$ticket.product_units|number_format:0:",":"."} {l s='fax credits' mod='expressmailing'}<br>{$ticket.product_desc|escape}
										{/if}
									</span>
									<span style="width:170px; display:inline-block">
										{if isset($ticket.promo_price) and ($ticket.promo_price > 0)}
											<strike>{$ticket.normal_price|number_format:2:",":"."} {l s='€' mod='expressmailing'}</strike>
											&nbsp;
											<font color="red"><b>{$ticket.promo_price|number_format:2:",":"."} {l s='€' mod='expressmailing'}</b></font>
											<br>{l s='(Let %.3f € / page)' mod='expressmailing' sprintf=($ticket.promo_price / $ticket.product_units)}
										{else}
											<b>{$ticket.normal_price|number_format:2:",":"."} {l s='€' mod='expressmailing'}</b><br>{l s='(Let %.3f € / page)' mod='expressmailing' sprintf=($ticket.normal_price / $ticket.product_units)}
										{/if}
									</span>
								</label>
							</div>
						{/foreach}
					</div>
				{/if}
				{if is_array($smarty_sms_tickets)}
					<div class="col-lg-6 ">
						{foreach $smarty_sms_tickets as $ticket}
							{if isset($ticket.promo_ending)}<font color="red" style="display: block">{l s='Promotion until' mod='expressmailing'} {$tool_date->getLocalizableDate($ticket.promo_ending)}</font>{/if}
							{if isset($ticket.promo_desc) and !empty($ticket.promo_desc)}<font color="red" style="display: block"><b>{$ticket.promo_desc|escape}</b></font>{/if}
							<div class="radio" {if isset($ticket.promo_ending)}style="margin-bottom: 8px"{/if}>
								<label>
									<input type="radio" name="product" value="{$ticket.product_ref|unescape}" />
									<span style="width:170px; display:inline-block">
										{if isset($ticket.promo_units) and ($ticket.promo_units > 0)}
											<strike>{$ticket.product_units|number_format:0:",":"."} {l s='sms credits' mod='expressmailing'}</strike>
											<br><font color="red"><b>{$ticket.promo_units|number_format:0:",":"."} {l s='sms credits' mod='expressmailing'}</b></font><br>{$ticket.product_desc|escape}
											{$ticket.product_units = $ticket.promo_units}
										{else}
											{$ticket.product_units|number_format:0:",":"."} {l s='sms credits' mod='expressmailing'}<br>{$ticket.product_desc|escape}</span>
										{/if}
									</span>
									<span style="width:170px; display:inline-block">
										{if isset($ticket.promo_price) and ($ticket.promo_price > 0)}
											<strike>{$ticket.normal_price|number_format:2:",":"."} {l s='€' mod='expressmailing'}</strike>
											&nbsp;
											<font color="red"><b>{$ticket.promo_price|number_format:2:",":"."} {l s='€' mod='expressmailing'}</b></font>
											<br>{l s='(Let %.3f € / sms)' mod='expressmailing' sprintf=($ticket.promo_price / $ticket.product_units)}
										{else}
											<b>{$ticket.normal_price|number_format:2:",":"."} {l s='€' mod='expressmailing'}</b><br>{l s='(Let %.3f € / sms)' mod='expressmailing' sprintf=($ticket.normal_price / $ticket.product_units)}
										{/if}
									</span>
								</label>
							</div>
						{/foreach}
					</div>
				{/if}
			</div>
		</div>
		<!-- /.form-wrapper -->
		<div class="panel-footer">
			<button type="submit" value="1" name="submitBuyStep0" class="btn btn-default pull-right"><i class="process-icon-cart"></i>{l s='Add to Cart' mod='expressmailing'}</button>
		</div>
	</div>
</form>
{/if}