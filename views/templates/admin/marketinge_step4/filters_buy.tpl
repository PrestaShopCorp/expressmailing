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

<div class="panel" style="height: 430px; margin-bottom: 0px; padding-bottom: 0px; background-color: #F2DEDE; box-shadow: 0 2px 0 rgba(0, 0, 0, 0.1);">
	<div class="panel-heading">{l s='Paying filters' mod='expressmailing'}</div>
	<div style="width: 100%; text-align: center">
		<h4>{l s='To boost your sales, target more precisely your contacts !' mod='expressmailing'}</h4>
		<div class="l-table" style="margin-left: auto; margin-right: auto;">
			<div>
				<div>
					<div class="l-table" style="text-align: left;">
						<div>
							<div class="nowrap">{l s='Age filter' mod='expressmailing'}</div>
							<div><img src="../img/admin/enabled.gif" alt=""/></div>
						</div>
						<div>
							<div class="nowrap">{l s='Civility filter' mod='expressmailing'}</div>
							<div><img src="../img/admin/enabled.gif" alt=""/></div>
						</div>
						<div>
							<div class="nowrap">{l s='Country filter' mod='expressmailing'}</div>
							<div><img src="../img/admin/enabled.gif" alt=""/></div>
						</div>
						<div>
							<div class="nowrap">{l s='Postal code filter' mod='expressmailing'}</div>
							<div><img src="../img/admin/enabled.gif" alt=""/></div>
						</div>
						<div>
							<div class="nowrap">{l s='Bying date filter' mod='expressmailing'}</div>
							<div><img src="../img/admin/enabled.gif" alt=""/></div>
						</div>
						<div>
							<div class="nowrap">{l s='Cancelled order filter' mod='expressmailing'}</div>
							<div><img src="../img/admin/enabled.gif" alt=""/></div>
						</div>
						<div>
							<div class="nowrap">{l s='Account creation date filter' mod='expressmailing'}</div>
							<div><img src="../img/admin/enabled.gif" alt=""/></div>
						</div>
						<div>
							<div class="nowrap">{l s='Promotion code filter' mod='expressmailing'}</div>
							<div><img src="../img/admin/enabled.gif" alt=""/></div>
						</div>
						<div>
							<div class="nowrap">{l s='Loyalty points filter' mod='expressmailing'}<font color="red"><sup>&nbsp;*</sup></font><br><font style="font-size: smaller"><font color="red"><sup>*</sup></font>&nbsp;{l s='Need Loyalty Module installed' mod='expressmailing'}</font></div>
							<div><img src="../img/admin/enabled.gif" alt=""/></div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div>&nbsp;</div>
		<a class="btn btn-default" href="index.php?controller=AdminMarketingBuy&product=email-filter-12-months&media=AdminMarketingEStep4&campaign_id={$campaign_id|intval}&token={Tools::getAdminTokenLite('AdminMarketingBuy')|escape:'html':'UTF-8'}"><i class="icon-shopping-cart"></i> &nbsp;{l s='49,00 € per year' mod='expressmailing'}</a>
	</div>
</div>
