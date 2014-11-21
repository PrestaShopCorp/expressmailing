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

<script type="text/javascript">
	var chartData = [
		{
			"label": "{l s='Redlist' mod='expressmailing'}",
			"value": {$nb_redlist|escape:'intval'}
		},
		{
			"label": "{l s='Suspended' mod='expressmailing'}",
			"value": {$nb_suspended|escape:'intval'}
		},
		{
			"label": "{l s='Already sent' mod='expressmailing'}",
			"value": {$nb_already_sent|escape:'intval'}
		},
		{
			"label": "{l s='To send' mod='expressmailing'}",
			"value": {$nb_to_send|escape:'intval'}
		}
	];
</script>

<div class="row">
    <div class="col-lg-12">
        <form id="configuration_form" class="defaultForm form-horizontal AdminMarketingEStep6" action="index.php?controller=AdminMarketingEStep7&token={Tools::getAdminTokenLite('AdminMarketingEStep7')|escape}" method="post" enctype="multipart/form-data" novalidate="">
            <input type="hidden" name="submitAddconfiguration" value="1">
            <div class="panel" id="fieldset_0">
                <div class="panel-heading">
                    <i class="icon-envelope-alt"></i> {l s='Final validation before sending (7)' mod='expressmailing'}
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
                            <div class="panel">
                                <div class="panel-heading">{l s='Details of the %d contacts' sprintf=$nb_total mod='expressmailing'}</div>
                                <div class="form-wrapper">
                                    <div class="form-group">
                                        <label class="control-label col-lg-6">
                                            {l s='Redlisted emails' mod='expressmailing'}
                                        </label>
                                        <label class="control-label col-lg-2">
                                            {$nb_redlist|escape:'intval'}
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-6">
                                            {l s='Unsubscribed / suspended emails' mod='expressmailing'}
                                        </label>
                                        <label class="control-label col-lg-2">
                                            {$nb_suspended|escape:'intval'}
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-6">
                                            {l s='Already sent emails' mod='expressmailing'}
                                        </label>
                                        <label class="control-label col-lg-2">
                                            {$nb_already_sent|escape:'intval'}
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-6">
                                            {l s='Emails to send' mod='expressmailing'}
                                        </label>
                                        <label class="control-label col-lg-2">
                                            {$nb_to_send|escape:'intval'}
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-6">
                                            {l s='Email weight' mod='expressmailing'}
                                        </label>
                                        <label class="control-label col-lg-2">
                                            {$mail_weight|escape:'intval'}
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-6">
                                            {l s='Email cost' mod='expressmailing'}
                                        </label>
                                        <label class="control-label col-lg-2">
                                            {$mail_cost|escape:'intval'}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="panel">
                                <div class="form-wrapper">
                                    <svg id="chart" style="margin-left: auto; margin-right: auto; height:258px; width: 400px"></svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer" align="center" style="height: auto">

					<table border="0" cellspacing="0" bgcolor="#000000">
					<tbody>
					<tr>
						<td>
							<table border="0" cellspacing="2" cellpadding="2" bgcolor="#FFFFFF">
							<tbody>
							<tr>
								<td colspan="3" align="center">
									{l s='To send this emailing, please type' mod='expressmailing'}
									<b>&laquo;&nbsp;{l s='YES' mod='expressmailing'}&nbsp;&raquo;</b>
									{l s='and then submit' mod='expressmailing'}
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td align="center"><input type="text" name="YES" size="20" style="text-align: center; text-transform:uppercase" autocomplete="off" maxlength="{{l s='YES' mod='expressmailing'}|count_characters}"></td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td align="right"><img border="0" src="../modules/expressmailing/img/left_arrow_history.gif" width="28" height="33"></td>
								<td align="center" style="padding: .5em">
									<button type="submit" value="1" id="configuration_form_submit_btn" name="sendCampaign" class="btn btn-default">
										<i class="process-icon-envelope"></i> {l s='Send campaign' mod='expressmailing'}
									</button>
								</td>
								<td align="left"><img border="0" src="../modules/expressmailing/img/right_arrow_history.gif" width="28" height="33"></td>
							</tr>
							</tbody>
							</table>
						</td>
					</tr>
					</tbody>
					</table>
                </div>
            </div>
        </form>
    </div>
</div>