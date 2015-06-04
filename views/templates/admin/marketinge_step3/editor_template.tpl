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

<form id="configuration_form_1" class="defaultForm form-horizontal AdminMarketingEStep3" action="index.php?controller=AdminMarketingEStep3&token={Tools::getAdminTokenLite('AdminMarketingEStep3')}" method="post" enctype="multipart/form-data" novalidate>
	<input type="hidden" name="submitAddconfiguration" value="1" />
	<div class="panel" id="fieldset_0_1_1">
		<div class="panel-heading">
			<i class="icon-edit"></i> {l s='Modify from the HTML editor (Step 3)' mod='expressmailing'}
		</div>
		<div class="form-wrapper">
			<div class="form-group hidden" >
				<label class="control-label col-lg-3">
					Ref :
				</label>
				<div class="col-lg-1 ">
					<input type="text" name="campaign_id" id="campaign_id" value="{$campaign_id|escape:'html':'UTF-8'}" class="" readonly="readonly"/>
				</div>
			</div>
			<div class="form-group">
				<div class="col-lg-12">
					<textarea name="campaign_html" id="campaign_html"   class="rte autoload_rte">{$campaign_html}</textarea>
				</div>
			</div>
		</div><!-- /.form-wrapper -->
		<div class="panel-footer">
			<button type="submit" value="1"	id="configuration_form_submit_btn_1" name="nextEmailingStep3" class="btn btn-default pull-right">
				<i class="process-icon-next"></i> {l s='Next' mod='expressmailing'}
			</button>
			<button type="submit"  class="btn btn-default pull-right" name="saveEmailingStep3"><i class="process-icon-save" ></i> {l s='Save' mod='expressmailing'}</button>
			<a href="index.php?controller=AdminMarketingEStep2&campaign_id={$campaign_id|escape:'html':'UTF-8'}&token={Tools::getAdminTokenLite('AdminMarketingEStep2')}"  class="btn btn-default" ><i class="process-icon-back" ></i> {l s='Back' mod='expressmailing'}</a>
		</div>
	</div>
</form>
