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

<div class="panel" style="height: 430px; margin-bottom: 0px; padding-bottom: 0px">
	<div class="panel-heading">{l s='Customer filters' mod='expressmailing'}</div>
	<div class="alert alert-info">{l s='To limit spam, free filters extract only subscribers who have really visited your site (shopping cart with valid IP address) !' mod='expressmailing'}</div>
	<div class="form-wrapper" style="overflow-x: hidden; overflow-y: auto; padding-left: 5px; margin-right: 0px">

		<div class="form-group">
			<div class="title text-left">{l s='Groups :' mod='expressmailing'}</div>
			<div class="col-lg-12" style="padding-left: 20px">
				{foreach $customers_groups as $group}
				<div class="checkbox gamification_notif">
					<label for="groups[{$group.id_group|intval}]"><input type="checkbox" value="1" class="checkbox-inline" id="groups[{$group.id_group|intval}]" name="groups[{$group.id_group|intval}]" {if $group.checked}checked="checked"{/if}>{$group.name|escape:'html':'UTF-8'} &nbsp; <span class="badge" style="font-size: smaller">{$group.total|intval}</span></label>
				</div>
				{/foreach}
			</div>
		</div>

		<div class="form-group">
			<div class="title text-left">{l s='Purchase language :' mod='expressmailing'}</div>
			<div class="col-lg-12" style="padding-left: 20px">
				{foreach $customers_langs as $lang}
				<div class="checkbox">
					<label for="langs[{$lang.id_lang|intval}]"><input type="checkbox" value="1" class="checkbox-inline" id="langs[{$lang.id_lang|intval}]" name="langs[{$lang.id_lang|intval}]" {if $lang.checked}checked="checked"{/if}>{$lang.name|escape:'html':'UTF-8'} &nbsp; <span class="badge" style="font-size: smaller">{$lang.total|intval}</span></label>
				</div>
				{/foreach}
			</div>
		</div>

		<div class="form-group">
			<div class="title text-left">{l s='Subscription filters :' mod='expressmailing'}</div>
			<div class="col-lg-12" style="padding-left: 20px">
				<div class="checkbox">
					<label for="subscriptions_campaign_optin"><input type="checkbox" {if $subscriptions_campaign_optin}checked="checked"{/if} value="1" class="checkbox-inline" id="subscriptions_campaign_optin" name="subscriptions_campaign_optin">{l s='All Optin customers' mod='expressmailing'} &nbsp; <span class="badge" style="font-size: smaller">{$customers_suscriptions.total_optin|intval}</span></label>
				</div>
				<div class="checkbox">
					<label for="subscriptions_campaign_newsletter"><input type="checkbox" {if $subscriptions_campaign_newsletter}checked="checked"{/if} value="1" class="checkbox-inline" id="subscriptions_campaign_newsletter" name="subscriptions_campaign_newsletter">{l s='All Newsletter suscribed customers' mod='expressmailing'} &nbsp; <span class="badge" style="font-size: smaller">{$customers_suscriptions.total_newsletter|intval}</span></label>
				</div>
				<div class="checkbox">
					<label for="subscriptions_campaign_active"><input type="checkbox" {if $subscriptions_campaign_active}checked="checked"{/if} value="1" class="checkbox-inline" id="subscriptions_campaign_active" name="subscriptions_campaign_active">{l s='All Active customers' mod='expressmailing'} &nbsp; <span class="badge" style="font-size: smaller">{$customers_suscriptions.total_active|intval}</span></label>
				</div>
			</div>

		</div>

	</div>
</div>