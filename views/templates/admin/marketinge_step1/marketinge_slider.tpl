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
	$(function () {
		var slider{$field_name|escape:javascript}Config = {
			range: false,
			min: {$min_value|escape:javascript},
			max: {$max_value|escape:javascript},
			step: {$step|escape:javascript},
			{if $preset_value}value: {$preset_value|escape:javascript},{/if}
			slide: function (event, ui) {
				$("#{$field_name|escape:javascript}").val(ui.value);
			}
		};
		$("#slider-{$field_name|escape:javascript}").slider(slider{$field_name|escape:javascript}Config);
		var change_{$field_name|escape:javascript} = function (evt) {
			var {$field_name|escape:javascript} = $("#{$field_name|escape}");
			$("#slider-{$field_name|escape:javascript}").slider("value", {$field_name|escape:javascript}.val());
		};
		$("#{$field_name|escape:javascript}").change(change_{$field_name|escape:javascript});
	});
</script>

<input class="col-lg-1" type="text" id="{$field_name|escape}" name="{$field_name|escape}" style="width: 4em" value="{$preset_value|escape:'intval'}"/>
<div class="slider-container col-lg-4" style="margin-top: 9px; margin-left: 10px; margin-right: 10px">
	<div id="slider-{$field_name|escape}"></div>
</div>
