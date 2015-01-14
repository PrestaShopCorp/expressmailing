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
		"label": String.fromCharCode(160) + "{$count_planned|escape:'intval'} {l s='planned' mod='expressmailing'}",
		"value": {$count_planned|escape:'intval'}
	},
	{
		"label": String.fromCharCode(160) + "{$count_cancelled|escape:'intval'} {l s='cancelled' mod='expressmailing'}",
		"value": {$count_cancelled|escape:'intval'}
	}
];
</script>

<div >
	<svg id="chart" style="margin-left: auto; margin-right: auto; height:258px; width: 400px"></svg>
</div>