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

<script>
	$(function () {
		var sliderAgeConfig = {
			range: true,
			min: 0,
			max: 100,
			values: [10, 90],
			slide: function (event, ui) {
				$("#min_age").val(ui.values[0]);
				$("#max_age").val(ui.values[1]);
			}
		};
		$("#min_age").val(sliderAgeConfig.values[0]);
		$("#max_age").val(sliderAgeConfig.values[1]);
		$("#slider_age").slider(sliderAgeConfig);

		var now = Date.now();
		var sliderByingDateConfig = {
			range: true,
			min: 0,
			max: now,
			values: [0, now],
			slide: function (event, ui) {
				var minTimestamp = ui.values[0];
				var maxTimestamp = ui.values[1];
				var minDate = new Date(minTimestamp);
				var maxDate = new Date(maxTimestamp);
				$("#min_bying_date").val(minDate.toLocaleString());
				$("#max_bying_date").val(maxDate.toLocaleString());
			}
		};
		var minTimestamp = sliderByingDateConfig.values[0];
		var maxTimestamp = sliderByingDateConfig.values[1];
		var minDate = new Date(minTimestamp);
		var maxDate = new Date(maxTimestamp);
		$("#min_bying_date").val(minDate.toLocaleString());
		$("#max_bying_date").val(maxDate.toLocaleString());
		$("#slider_bying_date").slider(sliderByingDateConfig);

		var sliderAccountCreationDateConfig = {
			range: true,
			min: 0,
			max: now,
			values: [0, now],
			slide: function (event, ui) {
				var minTimestamp = ui.values[0];
				var maxTimestamp = ui.values[1];
				var minDate = new Date(minTimestamp);
				var maxDate = new Date(maxTimestamp);
				$("#min_account_creation_date").val(minDate.toLocaleString());
				$("#max_account_creation_date").val(maxDate.toLocaleString());
			}
		};
		var minTimestamp = sliderAccountCreationDateConfig.values[0];
		var maxTimestamp = sliderAccountCreationDateConfig.values[1];
		var minDate = new Date(minTimestamp);
		var maxDate = new Date(maxTimestamp);
		$("#min_account_creation_date").val(minDate.toLocaleString());
		$("#max_account_creation_date").val(maxDate.toLocaleString());
		$("#slider_account_creation_date").slider(sliderAccountCreationDateConfig);
	});
</script>
<div class="form-wrapper">
	<div class="form-group">
		<label class="control-label col-lg-3">
			<span class="switch prestashop-switch fixed-width-lg">
				<input type="radio" name="campaign_redlist" id="campaign_redlist_on" value="1" checked="checked"/>
				<label  for="campaign_redlist_on">Anniversaire</label>
				<input type="radio" name="campaign_redlist" id="campaign_redlist_off" value="0"/>
				<label  for="campaign_redlist_off">Naissance</label>
				<a class="slide-button btn"></a>
			</span>
		</label>
	</div>
	<div class="form-group">
		<label class="control-label col-lg-3">
			{l s='Age filter' mod='expressmailing'}
		</label>
		<div class="col-lg-9">
			<div class="col-lg-1">
				<input type="text" id="min_age" name="min_age" />
			</div>
			<div class="col-lg-7">
				<div class="slider-container" style>
					<div id="slider_age"></div>
				</div>
			</div>
			<div class="col-lg-1">
				<input type="text" id="max_age" name="max_age" />
			</div>
		</div>
	</div>
	date d'anniversaire
	<div class="form-group">
		<label class="control-label col-lg-3">
			{l s='Civility filter' mod='expressmailing'}
		</label>
		<div class="col-lg-9 ">
			<div class="checkbox">
				<label for="civility[]_1"><input type="checkbox" name="civility[]_1" id="civility[]_1" class="checkbox-inline" value="1">{l s='Mr' mod='expressmailing'}</label>
				<label for="civility[]_2"><input type="checkbox" name="civility[]_2" id="civility[]_2" class="checkbox-inline" value="1">{l s='Mrs' mod='expressmailing'}</label>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-lg-3">
			{l s='Country filter' mod='expressmailing'}
		</label>
		<div class="col-lg-9 ">
			<div class="select">
				<select name="countries" id="countries" multiple="true">
					<option>AA</option>
					<option>BB</option>
					<option>CC</option>
					<option>DD</option>
					<option>EE</option>
					<option>FF</option>
					<option>GG</option>
					<option>HH</option>
					<option>II</option>
				</select>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-lg-3">
			{l s='Postal code filter' mod='expressmailing'}
		</label>
		<div class="col-lg-9 ">
			<div class="select">
				<select name="postal_codes" id="postal_codes" multiple="true">
					<option>01</option>
					<option>02</option>
					<option>03</option>
					<option>04</option>
					<option>05</option>
					<option>06</option>
					<option>07</option>
					<option>08</option>
					<option>09</option>
				</select>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-lg-3">
			{l s='Bying date filter' mod='expressmailing'}
		</label>
		<div class="col-lg-9">
			<div class="col-lg-2">
				<input type="text" id="min_bying_date" name="min_bying_date" readonly/>
			</div>
			<div class="col-lg-8">
				<div class="slider-container">
					<div id="slider_bying_date"></div>
				</div>
			</div>
			<div class="col-lg-2">
				<input type="text" id="max_bying_date" name="max_bying_date" readonly/>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-lg-3">
			{l s='Cancelled order filter' mod='expressmailing'}
		</label>
		<div class="col-lg-9 ">
			<div class="checkbox">
				<label for="cancelled_order"><input type="checkbox" name="cancelled_order" id="cancelled_order" class="checkbox-inline" value="1">Dernière commande annulée{l s='Customers who have a cancelled order' mod='expressmailing'}</label>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-lg-3">
			{l s='Account creation date filter' mod='expressmailing'}
		</label>
		<div class="col-lg-9">
			<div class="col-lg-2">
				<input type="text" id="min_account_creation_date" name="min_account_creation_date" readonly/>
			</div>
			<div class="col-lg-8">
				<div class="slider-container">
					<div id="slider_account_creation_date"></div>
				</div>
			</div>
			<div class="col-lg-2">
				<input type="text" id="max_account_creation_date" name="max_account_creation_date" readonly/>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-lg-3">
			{l s='Promotion code filter' mod='expressmailing'}
		</label>
		<div class="col-lg-9 ">
			<div class="checkbox">
				<label for="promotion_code"><input type="checkbox" name="promotion_code" id="promotion_code" class="checkbox-inline" value="1">{l s='Customers who used a promotion code' mod='expressmailing'}</label><br/>
				<input type="text" name="promotion_code_value" id="promotion_code_value" class="checkbox-inline" value="{l s='all' mod='expressmailing'}">
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-lg-3">
			{l s='Loyalty points filter' mod='expressmailing'}
		</label>
		<div class="col-lg-9 ">
			<div class="checkbox">
				<label for="promotion_code"><input type="checkbox" name="promotion_code" id="promotion_code" class="checkbox-inline" value="1">{l s='Customers who used a promotion code' mod='expressmailing'}</label><br/>
				<input type="text" name="promotion_code_value" id="promotion_code_value" class="checkbox-inline" value="{l s='all' mod='expressmailing'}">
			</div>
		</div>
	</div>
</div>