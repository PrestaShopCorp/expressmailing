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

<div class="row">
    <div class="col-lg-12">
		<form class="defaultForm form-horizontal AdminMarketingEStep6" action="#" method="post" enctype="multipart/form-data" novalidate="">
		<div class="panel" id="fieldset_0">
			<div class="panel-heading"><i class="icon-calendar-empty"></i> {l s='Broadcast days' mod='expressmailing'} <span class="badge">{count($days)|escape:'intval'}</span></div>
			<section>
				<nav>
					<ul class="nav nav-pills">
						{foreach $days as $day}
							<li {if $select_day == $day['day_api']}class="active"{/if}>
								<a href="{$current_index|escape:'quotes'}&stat_date={$day['day_api']|escape:'intval'}&token={$smarty.get.token}">
									<i class="icon-eye-open"></i>
									<span class="hidden-inline-xs">{$day['day_lang']|escape:'htmlall':'UTF-8'}</span>
								</a>
							</li>
						{/foreach}
					</ul>
				</nav>
			</section>
		</div>
		</form>
	</div>
</div>

<div class="row">
    <div class="col-lg-12">
		<form class="defaultForm form-horizontal AdminMarketingEStep6" action="#" method="post" enctype="multipart/form-data" novalidate="">
		<div class="panel" id="fieldset_0">
			<div class="panel-heading"><i class="icon-bar-chart"></i> {l s='Statistics for campaign' mod='expressmailing'} &laquo;&nbsp;{$campaign_name|escape:'htmlall':'UTF-8'}&nbsp;&raquo;</div>
			<table border="0" width="100%" cellspacing="5" cellpadding="5">
			<tr>
				<td class="stat_td">
					<div class="stat_list_block">
						<span class="stat_label">Envoyés</span>
						<span class="stat_value">
							<span>{$sent|escape:'intval'}</span>
						</span>
					</div>
				</td>
				 <td class="stat_td">
					<div class="stat_list_block">
						<span class="stat_label">Non<br>Envoyés</span>
						<span class="stat_value">
							<span>{$not_sent|escape:'intval'}</span>
						</span>
					</div>
				</td>
				 <td class="stat_td">
					<div class="stat_list_block">
						<span class="stat_label">Aboutis</span>
						<span class="stat_value">
							<span>{$delivered|escape:'intval'}</span>
						</span>
						<span class="ratio_label green">
							<span>{$ratio_delivered|escape:'intval'}%</span>
						</span>
					</div>
				</td>
				 <td class="stat_td">
					<div class="stat_list_block">
						<span class="stat_label">Non<br>Aboutis</span>
						<span class="stat_value">
							<span>{$not_delivered|escape:'intval'}</span>
						</span>
						<span class="ratio_label red">
							<span>{$ratio_not_delivered|escape:'intval'}%</span>
						</span>
					</div>
				</td>
				 <td class="stat_td">
					<div class="stat_list_block">
						<span class="stat_label">Ouverts</span>
						<span class="stat_value">
							<span>{$opened|escape:'intval'}</span>
						</span>
						<span class="ratio_label green">
							<span>{$ratio_opened|escape:'intval'}%</span>
						</span>
					</div>
				</td>
				 <td class="stat_td">
					<div class="stat_list_block">
						<span class="stat_label">Non<br>Ouverts</span>
						<span class="stat_value">
							<span>{$not_opened|escape:'intval'}</span>
						</span>
						<span class="ratio_label red">
							<span>{$ratio_not_opened|escape:'intval'}%</span>
						</span>
					</div>
				</td>
				 <td class="stat_td">
					<div class="stat_list_block">
						<span class="stat_label">Cliqueurs</span>
						<span class="stat_value">
							<span>{$unique_clickers|escape:'intval'}</span>
						</span>
						<span class="ratio_label green">
							<span>{$ratio_unique_clickers|escape:'intval'}%</span>
						</span>
					</div>
				</td>
				 <td class="stat_td">
					<div class="stat_list_block">
						<span class="stat_label">Liens<br>cliqués</span>
						<span class="stat_value">
							<span>{$all_clicks|escape:'intval'}</span>
						</span>
					</div>
				</td>
				 <td class="stat_td">
					<div class="stat_list_block">
						<span class="stat_label">Plaintes</span>
						<span class="stat_value">
							<span>{$abuses|escape:'intval'}</span>
						</span>
						<span class="ratio_label red">
							<span>{$ratio_abuses|escape:'intval'}%</span>
						</span>
					</div>
				</td>
				 <td class="stat_td">
					<div class="stat_list_block">
						<span class="stat_label">Désabon-<br>nements</span>
						<span class="stat_value">
							<span>{$unsubscribes|escape:'intval'}</span>
						</span>
						<span class="ratio_label red">
							<span>({$ratio_unsubscribes|escape:'intval'}%)</span>
						</span>
					</div>
				</td>
			</tr>
			</table>
		</div>
		</form>
	</div>
</div>