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

<div class="row" id="em_message_row">
	<ul class="nav nav-tabs" id="tabMessages">
		<li class="active">
			<a href="#messageEmail">
				<i class="icon-truck"></i>
				EMAIL
			</a>
		</li>
		<li class="">
			<a href="#messageSMS" id="tabSMS">
				<i class="icon-file-text"></i>
				SMS
			</a>
		</li>
	</ul>
	<div class="tab-content panel">
		<!-- Tab status -->
		<div class="tab-pane  in active" id="messageEmail"></div>
		<div class="tab-pane " id="messageSMS">
			<form class="form-horizontal hidden-print" style="display: none" id="em_sms_form" method="post" action="index.php?controller=AdminEmsms&token={Tools::getAdminTokenLite('AdminEmsms')|escape:'javascript':'UTF-8'}">
				<div class="form-group">
					<label class="control-label col-lg-3">
						<span>{l s='Recipient number' mod='expressmailing'}</span>
					</label>
					<div class="col-lg-9">
						<select name="sms_number" id="sms_number">
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-lg-3">
						<span>{l s='Preset message' mod='expressmailing'}</span>
					</label>
					<div class="col-lg-8">
						<select id="sms_messages">
							<option></option>
						</select>
					</div>
					<div class="col-lg-1">
						<button type="button" id="em_edit_messages" class="btn btn-default">
							<i class="icon-pencil"></i>
						</button>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-lg-3">
						<span>{l s='Sms text' mod='expressmailing'}</span>
					</label>
					<div class="col-lg-9">
						<textarea name="sms_text" id="sms_text"></textarea>
					</div>
				</div>
				<div class="form-group">
					<div class="col-lg-3">
						<button id="sendSMS" class="btn btn-primary">{l s='Send' mod='expressmailing'}</button>
					</div>
					<div class="col-lg-9 text-right">
						<span>{l s='Available credits' mod='expressmailing'} : </span><span id="em_available_credits" class="bold" style="margin-right: 2em">...</span> <button id="buysms" class="btn btn-default">{l s='Buy credits' mod='expressmailing'}</button>
					</div>
				</div>
				<div class="form-group">
					<div class="panel col-lg-12" id="em_history">
						<h3>{l s='History' mod='expressmailing'}<span class="badge"></span><button id='em-refresh-sms-history' class='btn btn-default' style='float: right'><i class='icon-refresh'></i></button></h3>
						<div id="em_sms_history_waiter" class="text-center" style='display: none; padding: 1em;'>
							<img src="../modules/expressmailing/views/img/progress-bar.gif" alt="Loading" />
						</div>
						<div class="table-responsive-row clearfix" style="max-height: 15em; overflow-y: auto;">
							<table class="table expressmailing_sms_history">
								<thead>
									<tr class="nodrag nodrop">
										<th>
											<span class="title_box">{l s='Date' mod='expressmailing'}</span>
										</th>
										<th>
											<span class="title_box">{l s='Message' mod='expressmailing'}</span>
										</th>
										<th>
											<span class="title_box">{l s='Status' mod='expressmailing'}</span>
										</th>
										<th>
											<span class="title_box">{l s='Type' mod='expressmailing'}</span>
										</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
						
					</div>
				</div>
			</form>
			<div id="em_sms_waiter" class="text-center">
				<img src="../modules/expressmailing/views/img/progress-bar.gif" alt="Loading" />
			</div>
		</div>
	</div>
	<script>
		$('#tabMessages a').click(function (e) {
			e.preventDefault()
			$(this).tab('show');
		})
	</script>
</div>
<div id="bying_dialog_sms" title="{l s='Buy sms tickets' mod='expressmailing'}">
    <div style="width: 100%; margin-right: auto; margin-left: auto; text-align: center">
        <br/><img src="../modules/expressmailing/views/img/progress-bar.gif" alt="loading" />
    </div>
</div>

<script type="text/javascript">
	$(function(){
		var id_order = {Tools::getValue('id_order')|intval};
		
		var mobiles_loaded = false;
		var messages_loaded = false;
		
		var messages_html = $("#messages > *").detach();
		messages_html.appendTo("#messageEmail");
		$("#messages").removeClass("well");
		var em_message_row = $("#em_message_row").detach();
		em_message_row.appendTo("#messages");
		
		function showForm(){
			if(mobiles_loaded && messages_loaded)
			{
				$("#em_sms_waiter").hide();
				$("#em_sms_form").show();
			}
		};
		
		// Load the list of available mobile numbers for the current customer
		var params = {
			order_id: id_order
		};
		callEmsms('listMobileNumberFromOrder', params, function(numbers){
			for(var id in numbers){
				$("#sms_number").append("<option value='" + numbers[id]['phone_mobile'] + "' data-idaddress='" + numbers[id]['id_address'] + "'>" + numbers[id]['alias'] + " - " + numbers[id]['phone_mobile'] + "</option>");
			}
			mobiles_loaded = true;
			showForm();
			
			// Init history for the selected number
			if($("#sms_number").val())
				loadHistory($("#sms_number").val());
		});
		
		// Edit recipient
		var dom_select_sms_number;
		$("#em_edit_number").click(function(){
			var id_address = $("#myselect option:selected").attr("data-idaddress");
			$("#sms_number").after("<input type='text' name='sms_number' id='sms_number_edit' value='" + $("#sms_number").val() + "' data-idaddress='" + id_address + "'>");
			dom_select_sms_number = $("#sms_number").detach();
		});
		
		// Load the list of available preset message
		var params = {
		};
		callEmsms('listPresetMessages', params, function(messages){
			$("#sms_messages").change(function(evt){
				$("#sms_text").val(messages[$(evt.target).val()]['content']);
			});
			for(var id in messages){
				$("#sms_messages").append("<option value='" + id + "'>" + messages[id]['name'] + " - " + messages[id]['content'].substring(0,30) + "[...]</option>");
			}
			messages_loaded = true;
			showForm();
		});
		
		// Edit preset messages
		$("#em_edit_messages").click(function(){
			var orig = {
				controller: "{Tools::getValue('controller')|escape:'javascript':'UTF-8'}",
				id_order: id_order,
				vieworder: "",
				focussms:""
			};
			window.location.href = "index.php?controller=AdminEmsms&token={Tools::getAdminTokenLite('AdminEmsms')|escape:'javascript':'UTF-8'}&orig=" + JSON.stringify(orig);
		});
		
		// Bying dialog
        var url_base = "index.php?controller=AdminMarketingX";
        var url_ajax = "&ajax=true";
        var url_token = "&token={Tools::getAdminTokenLite('AdminMarketingX')|escape:'html':'UTF-8'}";
        var dialogByingConfig = {
            autoOpen: false,
            resizable: true,
            position: 'center',
            modal: true,
            width: 820,
            height: 500,
			close: function() {
				location.href = "index.php?controller=AdminOrders&id_order=" + id_order + "&vieworder" + "&token={Tools::getAdminTokenLite("AdminOrders")|escape:'html':'UTF-8'}";
			}
        };
        $('#bying_dialog_sms').dialog(dialogByingConfig);
        $('#buysms').click(function (evt) {
			evt.preventDefault();
            $('#bying_dialog_sms').load(url_base + url_ajax + url_token + '&media=sms').dialog('open');
        });
		
		// Send the sms
		$("#sendSMS").click(function(evt){
			evt.preventDefault();
			var recipient = $("#sms_number").val();
			var content = $("#sms_text").val();
			var params = {
				recipient: recipient,
				content: content
			};
			callEmsms('sendSMS', params, function(result){
				loadHistory($("#sms_number").val(), true);
				alert("The SMS has been queued. Please check the status in the history");
			});
		});
		
		function loadAvailableCredits(){
			var params = {
			};
			callEmsms('getAvailableCredits', params, function(credits){
				if(credits.substring(0,1) == '0'){
					$('#sendSMS').prop('disabled', true);
					$('#sendSMS').addClass('btn-default');
					$('#sendSMS').removeClass('btn-primary');
					
				} else {
					$('#sendSMS').prop('disabled', false);
					$('#sendSMS').addClass('btn-primary');
					$('#sendSMS').removeClass('btn-default');
				}
				$('#em_available_credits').html(credits);
			});
		}
		loadAvailableCredits();
		
		// History
		function loadHistory(target, partialWaiter = false){
			var params = {
				target: target
			};
			$("#em_history h3>span.badge").text("...");
			
			if(!partialWaiter)
				$("#em_history tbody").empty();
			
			$("#em_sms_history_waiter").show();
			callEmsms('listSentMessages', params, function(result){
				$("#em_history tbody").empty();
				var autoRefreshed = false;
				$.each(result, function(index, value){
					if(!autoRefreshed && (value['state'] !== 'delivered' && value['state'] !== 'error' && value['state'] !== 'canceled')){
						autoRefreshed = true;
						setTimeout(function(){
							loadAvailableCredits();
							loadHistory(target, true);
						}, 5000);
					}
					var date = new Date(value['date'] * 1000);
					date = date.getFullYear().toString() + '-' + date.getMonth().toString() + '-' + date.getDay().toString();
					$("#em_history tbody").append('<tr class="odd"><td>' + date + '</td><td>' + value['message'] + '</td><td>' + value['state'] + '</td><td>' + value['type'] + '</td></tr>');
				});
				
				$("#em_history h3>span.badge").text(result.length);
				$("#em_sms_history_waiter").hide();
				$("#em_history").show();
			});
		}
		$("#sms_number").change(function(evt){
			loadHistory(evt.target.value);
		});
		$("#em-refresh-sms-history").click(function(evt){
			evt.preventDefault();
			loadHistory($("#sms_number").val());
		});
		
		// select sms tab if "focussms" is in page params
		var focussms = {Tools::isSubmit("focussms")|intval};
		if(focussms){
			$('html, body').animate({
				scrollTop: $("#tabSMS").offset().top - 200
			}, 500);
			$("#tabSMS").trigger("click");
		}
		
		// Ajax Call AdminEmsms
		function callEmsms(method, params, callback){
			var url = "index.php?controller=AdminEmsms&ajax=true&method=" + method + "&token={Tools::getAdminTokenLite('AdminEmsms')|escape:'javascript':'UTF-8'}";
			$.post( url, params, callback, "json");
		}
		
	});
</script>