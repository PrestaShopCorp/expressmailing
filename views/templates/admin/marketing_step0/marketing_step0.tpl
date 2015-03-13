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

<form id="configuration_form" class="defaultForm form-horizontal adminmarketingx" action="index.php?controller=AdminMarketingX&token={Tools::getAdminTokenLite('AdminMarketingX')|escape:'html':'UTF-8'}" method="post" enctype="multipart/form-data" novalidate>
	<div class="center small">
		<img src="../modules/expressmailing/img/{l s='config_en.png' mod='expressmailing'}" border="0"><br>&nbsp;
	</div>
	<div class="panel" id="fieldset_0">
		<div class="panel-heading">
			<i class="icon-cogs"></i>&nbsp;{l s='Send a mailing (all)' mod='expressmailing'}
			{if !empty($tool_tip)}
			<span class="panel-heading-action">
				<a class="list-toolbar-btn" href="#">
					<span class="label-tooltip" data-placement="left" data-html="true" data-original-title="{$tool_tip|escape:'html':'UTF-8'}" data-toggle="tooltip" title="">
						<i class="process-icon-help"></i>
					</span>
				</a>
			</span>
			{/if}
		</div>
		{if ($smarty.get.controller == 'AdminModules')}
			<div class="alert alert-info">
				{l s='For more simplicity, we have installed an "EXPRESS-MAILING" link into your "MODULES" tab on the left !' mod='expressmailing'}
			</div>
		{/if}
		<div class="form-wrapper">
			<div class="form-group">
				<label class="control-label col-lg-3">{l s='I want sending' mod='expressmailing'} </label>
				<div class="col-lg-9 ">

					<table style="margin: 3px">
					<tr>
						<td style="width:25px; vertical-align: top"><input type="radio" name="campaign_type" id="marketing_e" value="marketing_e" {if $smarty_email_disabled}disabled="disabled"{/if} {if ($smarty_media_checked === 'email')}checked="checked"{/if} /></td>
						<td style="width:170px; vertical-align: top; padding-top: 1px"><label for="marketing_e">{l s='An emailing' mod='expressmailing'}</label></td>
						<td style="width:300px; vertical-align: top; padding-top: 1px"><label for="marketing_e">{$smarty_email_credits|unescape}</label></td>
					</tr>
					</table>

					<table style="margin: 3px">
					<tr>
						<td style="width:25px; vertical-align: top"><input type="radio" name="campaign_type" id="marketing_f" value="marketing_f" {if $smarty_fax_disabled}disabled="disabled"{/if} {if ($smarty_media_checked === 'fax')}checked="checked"{/if} /></td>
						<td style="width:170px; vertical-align: top; padding-top: 1px"><label for="marketing_f">{l s='A fax-mailing' mod='expressmailing'}</label></td>
						<td style="width:300px; vertical-align: top; padding-top: 1px"><label for="marketing_f"><span class="no-bold">{$smarty_fax_credits|unescape}</span></label></td>
					</tr>
					</table>

					<table style="margin: 3px">
					<tr>
						<td style="width:25px; vertical-align: top"><input type="radio" name="campaign_type" id="marketing_s" value="marketing_s" {if $smarty_sms_disabled}disabled="disabled"{/if} {if ($smarty_media_checked === 'sms')}checked="checked"{/if} /></td>
						<td style="width:170px; vertical-align: top; padding-top: 1px"><label for="marketing_s">{l s='A sms-mailing' mod='expressmailing'}</label></td>
						<td style="width:300px; vertical-align: top; padding-top: 1px"><label for="marketing_s"><span class="no-bold">{$smarty_sms_credits|unescape}</span></label></td>
					</tr>
					</table>

				</div>
			</div>
		</div>
		<div class="panel-footer">
			{if !empty($tool_tip)}
			<div class="pull-left">
				<a class="btn btn-default dropdown-toggle center" data-toggle="dropdown" href="#">
					<i class="process-icon-stats" style="font-size: 22px; margin-top: 5px; margin-bottom: -5px"><span class="caret" style="margin-left: 6px"></span></i>
					{l s='My statistics' mod='expressmailing'}
				</a>
				<ul class="dropdown-menu pull-left">
					<li><a class="sorter sort-name" href="index.php?controller=AdminMarketingEList&token={Tools::getAdminTokenLite('AdminMarketingEList')|escape:'html':'UTF-8'}">{l s='My emailing statistics' mod='expressmailing'}</a></li>
					<li><a class="sorter sort-date" href="index.php?controller=AdminMarketingFList&token={Tools::getAdminTokenLite('AdminMarketingFList')|escape:'html':'UTF-8'}">{l s='My fax statistics' mod='expressmailing'}</a></li>
					<li><a class="sorter sort-size" href="index.php?controller=AdminMarketingSList&token={Tools::getAdminTokenLite('AdminMarketingSList')|escape:'html':'UTF-8'}">{l s='My sms statistics' mod='expressmailing'}</a></li>
				</ul>
			</div>
			{/if}
			<button type="submit" value="1" id="configuration_form_submit_btn" name="submitMarketingAll" class="btn btn-default pull-right"><i class="process-icon-next"></i>{l s='Next' mod='expressmailing'}</button>
		</div>
	</div>
</form>