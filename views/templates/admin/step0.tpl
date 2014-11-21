{*
* 2014 (c) Axalone France - Express-Mailing
*
* This file is a commercial module for Prestashop
* Do not edit or add to this file if you wish to upgrade PrestaShop or
* customize PrestaShop for your needs please refer to
* http://www.express-mailing.com for more information.
*
* @author    Axalone France <info@express-mailing.com>
* @copyright 2014 (c) Axalone France
* @license   http://www.express-mailing.com
*}

<form id="configuration_form" class="defaultForm form-horizontal adminmarketing" action="index.php?controller=AdminMarketing&token={Tools::getAdminTokenLite('AdminMarketing')|escape}" method="post" enctype="multipart/form-data" novalidate>
	<div class="center small">
		<img src="../modules/expressmailing/img/config_fr.png" border="0"><br>&nbsp;
	</div>
	<div class="panel" id="fieldset_0">
		<div class="panel-heading">
			<i class="icon-cogs"></i>&nbsp;{l s='Send a mailing (all)' mod='expressmailing'}
		</div>
		{if ($smarty.get.controller == 'AdminModules')}
		<div class="alert alert-info">
			{l s='Have a look to the MARKETING menu' mod='expressmailing'}
		</div>
		{/if}
		<div class="form-wrapper">
			<div class="form-group">
				<label class="control-label col-lg-3">{l s='I want sending' mod='expressmailing'} </label>
				<div class="col-lg-9 ">
					<div class="radio ">
						<label><input type="radio" name="campaign_type" id="marketing_e" value="marketing_e" {if ($smarty.get.controller == 'AdminMarketingE') || ($smarty.get.controller == 'AdminMarketing') || ($smarty.get.controller == 'AdminModules')}checked="checked"{/if} /><span style="width:160px; display:inline-block">{l s='An emailing' mod='expressmailing'}</span><b>{l s='Free of charge' mod='expressmailing'}</b></label>
					</div>
					<div class="radio ">
						<label><input type="radio" name="campaign_type" id="marketing_f" value="marketing_f" disabled="disabled" {if ($smarty.get.controller == 'AdminMarketingF')}checked="checked"{/if} /><span style="width:160px; display:inline-block">{l s='A fax-mailing' mod='expressmailing'}</span><b>{l s='Fax price per page' mod='expressmailing'}</b></label>
					</div>
					<div class="radio ">
						<label><input type="radio" name="campaign_type" id="marketing_s" value="marketing_s" disabled="disabled" {if ($smarty.get.controller == 'AdminMarketingS')}checked="checked"{/if} /><span style="width:160px; display:inline-block">{l s='A sms-mailing' mod='expressmailing'}</span><b>{l s='Sms price per page' mod='expressmailing'}</b></label>
					</div>
				</div>
			</div>
		</div>
		<!-- /.form-wrapper -->
		<div class="panel-footer">
			<button type="submit" value="1" id="configuration_form_submit_btn" name="submitMarketingAll" class="btn btn-default pull-right"><i class="process-icon-next"></i>{l s='Next' mod='expressmailing'}</button>
		</div>
	</div>
</form>