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

<div class="row">
    <div class="col-lg-12">
        <form id="configuration_form" class="defaultForm form-horizontal AdminMarketingEStep4" action="index.php?controller=AdminMarketingEStep4&token={Tools::getAdminTokenLite('AdminMarketingEStep4')|escape}" method="post" enctype="multipart/form-data" novalidate="">
            <input type="hidden" name="submitAddconfiguration" value="1">
            <div class="panel" id="fieldset_0">
                <div class="panel-heading">
                    <i class="icon-envelope-alt"></i> {l s='Recipients configuration (4)' mod='expressmailing'}
                </div>
                <div class="form-wrapper">
                    <div class="form-group">
                        <label class="control-label col-lg-3">
                            Ref :
                        </label>
                        <div class="col-lg-1">
                            <input type="text" name="campaign_id" id="campaign_id" value="{$campaign_id|escape:'intval'}" class="" readonly="readonly">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-6">
                            <div class="panel" style="min-height: 24em; padding-bottom: 0; margin-bottom: 0">
                                <div class="panel-heading">{l s='Free filters' mod='expressmailing'}</div>
                                {$free_filter_inputs|escape:'none'}
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="panel" style="min-height: 24em; background-color: #F2DEDE; box-shadow: 0 2px 0 rgba(0, 0, 0, 0.1); padding-bottom: 0; margin-bottom: 0">
								<div class="panel-heading">{l s='Paying filters' mod='expressmailing'}</div>
                                {$paying_filter_inputs|escape:'none'}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer" align="center">
					<button type="submit" value="1"	id="customer_form_submit_btn" name="refreshEmailingStep4" class="btn btn-default">
						<i class="process-icon-refresh"></i> {l s='Apply settings' mod='expressmailing'}
					</button>
                </div>
            </div>
        </form>
    </div>
</div>