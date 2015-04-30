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
        <img src="../modules/expressmailing/views/img/{l s='config_en.png' mod='expressmailing'}" border="0"><br>&nbsp;
    </div>
    <div class="panel" style="text-align: center">
        <div style="display: inline-block; width:24%; min-width: 180px; text-align: left; vertical-align: top; margin-bottom: 1.5em">
            <img src="../modules/expressmailing/views/img/email.png" border="0" style="display: inline-block; margin-bottom: .5em">
            <div style="display: inline-block; vertical-align: top; padding-left: 1em; text-align: left; width: 18em">
                <span style="display: block; font-size: 10pt; font-weight: bold">Vos crédits email</span>
                <span style="display: block; font-size: 9pt">{$smarty_remaining_email_credits|unescape}</span>
                <div style="display: block; padding-top: 4px">
                    <a href="index.php?controller=AdminMarketingEList&token={Tools::getAdminTokenLite('AdminMarketingEList')|escape:'html':'UTF-8'}" class="btn btn-default" style="text-align: left; box-shadow: none; border: none;"><i class="icon-dashboard"></i> &nbsp;{l s='My email stats' mod='expressmailing'}</a><br/>
                    <a class="btn btn-default" id="em_bying_link_email" style="text-align: left; box-shadow: none; border: none;"><i class="icon-shopping-cart"></i> &nbsp;{l s='Increase capacity ?' mod='expressmailing'}</a>
                    {if $smarty_email_promotion}
                        <br/><span class="badge"><i class="icon-star"></i> <small>{l s='Discount' mod='expressmailing'}</small></span>
                    {/if}
                </div>
            </div>
        </div>
        <div style="display: inline-block; width:24%; min-width: 180px; text-align: left; vertical-align: top; margin-bottom: 1.5em">
            <img src="../modules/expressmailing/views/img/fax.png" border="0" style="display: inline-block; margin-bottom: .5em">
            <div style="display: inline-block; vertical-align: top; padding-left: 1em; text-align: left; width: 18em">
                <span style="display: block; font-size: 10pt; font-weight: bold">Vos crédits fax</span>
                <span style="display: block; font-size: 9pt">{$smarty_remaining_fax_credits|unescape}</span>
                <div style="display: block; padding-top: 4px">
                    <a href="index.php?controller=AdminMarketingFList&token={Tools::getAdminTokenLite('AdminMarketingFList')|escape:'html':'UTF-8'}" class="btn btn-default" style="text-align: left; box-shadow: none; border: none;"><i class="icon-dashboard"></i> &nbsp;{l s='My fax stats' mod='expressmailing'}</a><br/>
                    <a class="btn btn-default" id="em_bying_link_fax" style="text-align: left; box-shadow: none; border: none;"><i class="icon-shopping-cart"></i> &nbsp;{l s='Buy tickets' mod='expressmailing'}</a>
                    {if $smarty_fax_promotion}
                        <br/><span class="badge"><i class="icon-star"></i> <small>{l s='Discount' mod='expressmailing'}</small></span>
                    {/if}
                </div>
            </div>
        </div>
        <div style="display: inline-block; width:24%; min-width: 180px; text-align: left; vertical-align: top; margin-bottom: 1.5em">
            <img src="../modules/expressmailing/views/img/sms.png" border="0" style="display: inline-block; margin-bottom: .5em">
            <div style="display: inline-block; vertical-align: top; padding-left: 1em; text-align: left; width: 18em">
                <span style="display: block; font-size: 10pt; font-weight: bold">Vos crédits sms</span>
                <span style="display: block; font-size: 9pt">{$smarty_remaining_sms_credits|unescape}</span>
                <div style="display: block; padding-top: 4px">
                    <a href="index.php?controller=AdminMarketingSList&token={Tools::getAdminTokenLite('AdminMarketingSList')|escape:'html':'UTF-8'}" class="btn btn-default" style="text-align: left; box-shadow: none; border: none;"><i class="icon-dashboard"></i> &nbsp;{l s='My sms stats' mod='expressmailing'}</a><br/>
                    <a class="btn btn-default" id="em_bying_link_sms" style="text-align: left; box-shadow: none; border: none;"><i class="icon-shopping-cart"></i> &nbsp;{l s='Buy tickets' mod='expressmailing'}</a>
                    {if $smarty_sms_promotion}
                        <br/><span class="badge"><i class="icon-star"></i> <small>{l s='Discount' mod='expressmailing'}</small></span>
                    {/if}
                </div>
            </div>
        </div>
        <div style="display: inline-block; width:24%; min-width: 180px; text-align: left; vertical-align: top; margin-bottom: 1.5em">
            <img src="../modules/expressmailing/views/img/audio.png" border="0" style="display: inline-block; margin-bottom: .5em">
            <div style="display: inline-block; vertical-align: top; padding-left: 1em; text-align: left; width: 18em">
                <span style="display: block; font-size: 10pt; font-weight: bold">{l s='Coming soon' mod='expressmailing'}</span>
                <div style="display: block; padding-top: 4px">
                    <a href="https://www.express-mailing.com" target="_blank" class="btn btn-default" style="text-align: left; box-shadow: none; border: none;"><i class="icon-hand-right"></i> &nbsp;{l s='More informations' mod='expressmailing'}</a>
                </div>
            </div>
        </div>
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
            <div class="form-group" style="font-size: 10pt;">
                <label class="control-label col-lg-3" style="margin-top: 3px"><span>{l s='I want sending' mod='expressmailing'}</span></label>
                <div class="col-lg-9 ">
                    <table style="margin: 3px">
                        <tr>
                            <td style="vertical-align: middle; width: 30px; text-align: center"><div style="border-radius: 2px; width: 25px; height: 11px; background-color: rgb(148, 190, 42); margin-top: 3px"></div></td>
                            <td style="width:170px; vertical-align: middle; padding-top: 1px">
								<a href="index.php?campaign_type=marketing_e&controller=AdminMarketingX&token={Tools::getAdminTokenLite('AdminMarketingX')|escape:'html':'UTF-8'}" class="btn btn-default{if $smarty_email_disabled} disabled{/if}" style="text-align: left; box-shadow: none; border: none; font-weight: bold; font-size:10pt">{l s='An emailing' mod='expressmailing'}</a>
							</td>
                            <td style="width:400px; vertical-align: middle; padding-top: 3px">{$smarty_email_capacity|unescape}</td>
                        </tr>
                    </table>

                    <table style="margin: 3px">
                        <tr>
                            <td style="vertical-align: middle; width: 30px; text-align: center"><div style="border-radius: 2px; width: 25px; height: 11px; background-color: rgb(199, 111, 143); margin-top: 3px"></div></td>
                            <td style="width:170px; vertical-align: middle; padding-top: 1px">
								<a href="index.php?campaign_type=marketing_f&controller=AdminMarketingX&token={Tools::getAdminTokenLite('AdminMarketingX')|escape:'html':'UTF-8'}" class="btn btn-default {if $smarty_email_disabled}disabled{/if}" style="text-align: left; box-shadow: none; border: none; font-weight: bold; font-size:10pt">{l s='A fax-mailing' mod='expressmailing'}</a>
							</td>
                        </tr>
                    </table>

                    <table style="margin: 3px">
                        <tr>
                            <td style="vertical-align: middle; width: 30px; text-align: center"><div style="border-radius: 2px; width: 25px; height: 11px; background-color: rgb(117, 141, 188); margin-top: 3px"></div></td>
                            <td style="width:170px; vertical-align: middle; padding-top: 1px">
								<a href="index.php?campaign_type=marketing_s&controller=AdminMarketingX&token={Tools::getAdminTokenLite('AdminMarketingX')|escape:'html':'UTF-8'}" class="btn btn-default {if $smarty_email_disabled}disabled{/if}" style="text-align: left; box-shadow: none; border: none; font-weight: bold; font-size:10pt">{l s='A sms-mailing' mod='expressmailing'}</a>
							</td>
                        </tr>
                    </table>

                </div>
            </div>   
        </div>
    </div>
</form>

<div id="bying_dialog_email" title="{l s='Increase capacity ?' mod='expressmailing'}">
    <div style="width: 100%; margin-right: auto; margin-left: auto; text-align: center">
        <br><img src="../modules/expressmailing/views/img/progress-bar.gif" alt="" />
    </div>
</div>
<div id="bying_dialog_fax" title="{l s='Buy fax tickets' mod='expressmailing'}">
    <div style="width: 100%; margin-right: auto; margin-left: auto; text-align: center">
        <br><img src="../modules/expressmailing/views/img/progress-bar.gif" alt="" />
    </div>
</div>
<div id="bying_dialog_sms" title="{l s='Buy sms tickets' mod='expressmailing'}">
    <div style="width: 100%; margin-right: auto; margin-left: auto; text-align: center">
        <br><img src="../modules/expressmailing/views/img/progress-bar.gif" alt="" />
    </div>
</div>

<script type="text/javascript">

    $(function ()
    {
        var progresshtml = '<div style="width: 100%; margin-right: auto; margin-left: auto; text-align: center">\
                    <br><img src="../modules/expressmailing/views/img/progress-bar.gif" alt="" /></div>';
        var url_base = "index.php?controller=AdminMarketingX";
        var url_ajax = "&ajax=true";
        var url_token = "&token={Tools::getAdminTokenLite('AdminMarketingX')|escape:'html':'UTF-8'}";

        var dialogByingConfig = {
            autoOpen: false,
            resizable: true,
            position: 'center',
            modal: true,
            width: 820,
            height: 450,
            buttons: {
                "{l s='Close' mod='expressmailing'}": function () {
                    $(this).dialog("close");
                }
            }
        };

        $('#bying_dialog_email').dialog(dialogByingConfig);
        $('#bying_dialog_fax').dialog(dialogByingConfig);
        $('#bying_dialog_sms').dialog(dialogByingConfig);

        $('#em_bying_link_email').click(function () {
            $('#bying_dialog_email').load(url_base + url_ajax + url_token + '&media=email').dialog('open');
        });
        $('#em_bying_link_fax, #em_bying_link_fax2').click(function () {
            $('#bying_dialog_fax').load(url_base + url_ajax + url_token + '&media=fax').dialog('open');
        });
        $('#em_bying_link_sms, #em_bying_link_sms2').click(function () {
            $('#bying_dialog_sms').load(url_base + url_ajax + url_token + '&media=sms').dialog('open');
        });

    });

</script>