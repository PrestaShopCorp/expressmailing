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

<script type="text/javascript">
	var chartData = [
		{
			"label": String.fromCharCode(160) + "{$nb_redlist|intval} {l s='redlist' mod='expressmailing'}",
			"value": {$nb_redlist|intval}
		},
		{
			"label": String.fromCharCode(160) + "{$nb_suspended|intval} {l s='suspended' mod='expressmailing'}",
			"value": {$nb_suspended|intval}
		},
		{
			"label": String.fromCharCode(160) + "{$nb_already_sent|intval} {l s='already sent' mod='expressmailing'}",
			"value": {$nb_already_sent|intval}
		},
		{
			"label": String.fromCharCode(160) + "{$nb_to_send|intval} {l s='to send' mod='expressmailing'}",
			"value": {$nb_to_send|intval}
		}
	];
</script>

<div class="row">
    <div class="col-lg-12">

		{if !$campaign_sended}

			<form id="configuration_form" class="defaultForm form-horizontal" action="index.php?controller=AdminMarketingEStep8&token={Tools::getAdminTokenLite('AdminMarketingEStep8')|escape:'html':'UTF-8'}" method="post" enctype="multipart/form-data" novalidate="">
				<div class="panel" id="fieldset_0">
					<div class="panel-heading">
						<i class="icon-envelope-alt"></i> {l s='Final validation before sending (step 8)' mod='expressmailing'}
					</div>
					<div class="form-wrapper">
						<div class="form-group hidden">
							<label class="control-label col-lg-3">
								Ref :
							</label>
							<div class="col-lg-1">
								<input type="text" name="campaign_id" id="campaign_id" value="{$campaign_id|intval}" class="" readonly="readonly">
							</div>
						</div>
						<div class="form-group">
							<div class="col-lg-6">
								<div class="panel">
									<div class="panel-heading">{l s='Details of the %d contacts' sprintf=$nb_total mod='expressmailing'}</div>
									<div class="form-wrapper">
										<div class="form-group">
											<label class="control-label col-lg-6">
												{l s='Redlisted emails :' mod='expressmailing'}
											</label>
											<label class="control-label col-lg-2">
												{$nb_redlist|intval}
											</label>
										</div>
										<div class="form-group">
											<label class="control-label col-lg-6">
												{l s='Unsubscribed / suspended emails :' mod='expressmailing'}
											</label>
											<label class="control-label col-lg-2">
												{$nb_suspended|intval}
											</label>
										</div>
										<div class="form-group">
											<label class="control-label col-lg-6">
												{l s='Already sent emails :' mod='expressmailing'}
											</label>
											<label class="control-label col-lg-2">
												{$nb_already_sent|intval}
											</label>
										</div>
										<div class="form-group">
											<label class="control-label col-lg-6">
												{l s='Emails ready to send :' mod='expressmailing'}
											</label>
											<label class="control-label col-lg-2">
												{$nb_to_send|intval}
											</label>
										</div>
										<div class="form-group">
											<label class="control-label col-lg-6">
												{l s='Email weight :' mod='expressmailing'}
											</label>
											<label class="control-label col-lg-2">
												{$mail_weight|escape:'html':'UTF-8'}
											</label>
										</div>
										<div class="form-group">
											<label class="control-label col-lg-6">
												{l s='Email cost :' mod='expressmailing'}
											</label>
											<label class="control-label col-lg-2">
                                                {if $mail_cost > 1}
                                                    {$mail_cost|escape:'html':'UTF-8'} {l s='credits' mod='expressmailing'}
                                                {else}
                                                    {$mail_cost|intval} {l s='credit' mod='expressmailing'}
                                                {/if}
											</label>
										</div>
										<div class="form-group">
											<label class="control-label col-lg-6">
												{l s='Available credits :' mod='expressmailing'}
											</label>
											<label class="control-label col-lg-2">
												{$available_credits|escape:'html':'UTF-8'} {l s='credits' mod='expressmailing'}
											</label>
										</div>
										<div class="form-group">
											<label class="control-label col-lg-6">
												{l s='Required credits :' mod='expressmailing'}
											</label>
											<label class="control-label col-lg-2">
												{$nb_to_send * $mail_cost|escape:'html':'UTF-8'} {l s='credits' mod='expressmailing'}
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
					{if $formula == 'Tickets' && $available_credits < $nb_to_send * $mail_cost}
						<div class='text-center'>
							<div style='display: inline-block; border: 1px solid lightgray; padding: 1em; border-radius: 5px' class='text-center red bold'>
								<span>{l s='Insufficient credits' mod='expressmailing'}</span><br/>
								<span>{l s='In order to send this campaign, you need to refill your account' mod='expressmailing'}</span><br/><br/>
								<a class="btn btn-default" id="em_bying_link_email"><i class="icon-shopping-cart"></i> &nbsp;{l s='Buy email credits' mod='expressmailing'}</a>
							</div>
						</div>
					{else}
						{$footer_validation|unescape}
					{/if}
				</div>
			</form>

		{else}

			<!-- Stats -->
			<div class="panel" id="fieldset_0">
				<a class="btn btn-default" href="index.php?controller=AdminMarketingEList&token={Tools::getAdminTokenLite('AdminMarketingEList')|escape:'html':'UTF-8'}">
					<i class="process-icon-stats"></i> {l s='Display statistics' mod='expressmailing'}
				</a>
			</div>

		{/if}
    </div>
</div>
<div id="bying_dialog_email" title="{l s='Buy email credits' mod='expressmailing'}">
    <div style="height: 100%; width: 100%; padding: 0px">
        <br/><img src="../modules/expressmailing/views/img/progress-bar.gif" alt="loading" />
    </div>
</div>
<script type="text/javascript">

    $(function () {
        var current_location = "index.php?controller=AdminMarketingEStep8";
		var current_location_campaign = "&campaign_id={Tools::getValue('campaign_id')|escape:'html':'UTF-8'}";
		var current_location_token = "&token={Tools::getAdminTokenLite('AdminMarketingEStep8')|escape:'html':'UTF-8'}";

        var dialogByingConfig = {
            autoOpen: false,
            resizable: true,
            position: 'center',
            modal: true,
            width: 820,
            height: 500,
			close: function() {
				location.href = current_location + current_location_campaign + current_location_token;
			}
        };

        $('#bying_dialog_email').dialog(dialogByingConfig);

		var url_base = "index.php?controller=AdminMarketingX";
        var url_ajax = "&ajax=true";
        var url_token = "&token={Tools::getAdminTokenLite('AdminMarketingX')|escape:'html':'UTF-8'}";
		
        $('#em_bying_link_email').click(function () {
            $('#bying_dialog_email').load(url_base + url_ajax + url_token + '&media=email').dialog('open');
        });
    });

</script>