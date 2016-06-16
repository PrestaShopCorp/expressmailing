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
<div class="bootstrap panel">
	<div class="row">
		<div class="col-lg-8 em-padding-r10px">
			<div class="row">
				<div class="col-lg-12 text-center row-margin-bottom">
					<img src="../modules/expressmailing/views/img/{l s='config_en.png' mod='expressmailing'}" alt="logo" class="em-space-inline em-maxw-100" />
					<img src="../modules/expressmailing/views/img/certified_prestashop.png" alt="certified by prestashop" class="em-space-inline"/>
				</div>
				<div class="em-center-block em-maxw-60em">
					<p>{l s='With more than %d years of experience in direct marketing, we provide the ideal tool to broadcast your marketing campaigns (emailing, faxing & Sms).' mod='expressmailing' sprintf=$smarty.now|date_format:"%Y" - 2002}</p>
					<p class="text-center">
						<img src="../modules/expressmailing/views/img/email.png" class="em-media-img"/>
						<img src="../modules/expressmailing/views/img/fax.png" class="em-media-img"/>
						<img src="../modules/expressmailing/views/img/sms.png" class="em-media-img"/>
					</p>
					<p>{l s='Fully integrated with PrestaShop Express-Mailing module allows you to precisely select recipients of the campaigns from your PrestaShop customers or visitors by using various filters. You can easily send them newsletters, discounts or special offers, report your season sales or announce your corporate events.' mod='expressmailing'}</p>
					<p>{l s='Quick and intuitive, you can send your email campaigns to 200 or even 10 000 contacts in less than 5 minutes.' mod='expressmailing'}</p>
					<p>{l s='Experience the Express-Mailing module now, and boost your sales !' mod='expressmailing'}</p>
					<img src="../modules/expressmailing/views/img/graph.png" class="em-center-block em-maxw-100 em-width-500px"/>
				</div>
			</div>
			<div class="row">
				<div class="em-center-block text-center row-margin-bottom em-maxw-60em">
					<hr/>
					<h2 class="text-uppercase em-nomargin-top">{l s='Quick training' mod='expressmailing'}</h2>
					<div class="col-lg-1"><img src="../modules/expressmailing/views/img/help.gif"/></div>
					<div class="col-lg-7"><p class="text-left">{l s='For easier handling, we offer quick trainings in 10-15 minutes by phone :' mod='expressmailing'}</p></div>
					<div class="col-lg-4"><img src="../modules/expressmailing/views/img/{l s='tel_en.gif' mod='expressmailing'}" class="em-maxw-100"/></div>
				</div>
			</div>
			<div class="row">
				<div class="em-center-block text-center row-margin-bottom em-maxw-60em">
					<hr/>
					<h2 class="text-uppercase em-nomargin-top">{l s='Loss leader pricing' mod='expressmailing'}</h2>
					<table class="media_choice text-left">
						<tr>
							<td class="colorcell"><div class="greencell"></div></td>
							<td class="linkcell">{l s='Emailing' mod='expressmailing'}</td>
							<td class="commentcell">
								{l s='Lowest price' mod='expressmailing'}
								<b>&nbsp;:&nbsp;</b>
								{capture assign="smarty_email_lowest_price"}{$smarty_email_lowest_price|escape:'htmlall':'UTF-8'|string_format:"%.3f"|replace:'0.':'0,'|rtrim:'0'}{/capture}
								{capture assign="eprice"}{l s='From %s € per email' mod='expressmailing' sprintf=$smarty_email_lowest_price}{/capture}
								{$eprice|escape:'html':'UTF-8'}
								{if !empty($smarty_email_promotion)}
									<span class="badge"><i class="icon-star"></i> <small>{l s='Discount' mod='expressmailing'}</small></span>
								{/if}
							</td>
						</tr>
						<tr>
							<td class="colorcell"><div class="redcell"></div></td>
							<td class="linkcell">{l s='Fax' mod='expressmailing'}</td>
							<td class="commentcell">
								{l s='Lowest price to Metropolitan France' mod='expressmailing'}
								<b>&nbsp;:&nbsp;</b>
								{capture assign="smarty_fax_lowest_price"}{$smarty_fax_lowest_price|escape:'htmlall':'UTF-8'|string_format:"%.3f"|replace:'0.':'0,'|rtrim:'0'}{/capture}
								{capture assign="fprice"}{l s='From %s € per page' mod='expressmailing' sprintf=$smarty_fax_lowest_price}{/capture}
								{$fprice|escape:'html':'UTF-8'}
								{if !empty($smarty_fax_promotion)}
									<b>&nbsp;-&nbsp;</b>
									<span class="badge"><i class="icon-star"></i> <small>{l s='Discount' mod='expressmailing'}</small></span>
								{/if}
							</td>
						</tr>
						<tr>
							<td class="colorcell"><div class="bluecell"></div></td>
							<td class="linkcell">{l s='Sms' mod='expressmailing'}</td>
							<td class="commentcell">
								{l s='Lowest price to Metropolitan France' mod='expressmailing'}
								<b>&nbsp;:&nbsp;</b>
								{capture assign="smarty_sms_lowest_price"}{$smarty_sms_lowest_price|escape:'htmlall':'UTF-8'|string_format:"%.3f"|replace:'0.':'0,'|rtrim:'0'}{/capture}
								{capture assign="fprice"}{l s='From %s € per sms' mod='expressmailing' sprintf=$smarty_sms_lowest_price}{/capture}
								{$fprice|escape:'html':'UTF-8'}
								{if !empty($smarty_sms_promotion)}
									<b>&nbsp;-&nbsp;</b>
									<span class="badge"><i class="icon-star"></i> <small>{l s='Discount' mod='expressmailing'}</small></span>
								{/if}
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
		<div class="col-lg-4">
			<div class="em-fixed-col-lg">
				<div class="row row-margin-bottom intro-left-blocs">
					<div class="col-lg-12 text-center">
						<h2 class="text-uppercase em-nomargin-top">{l s='Subscription' mod='expressmailing'}</h2>
						<a href="index.php?controller=AdminMarketingInscription&token={Tools::getAdminTokenLite('AdminMarketingInscription')|escape:'html':'UTF-8'}" class="btn btn-primary btn-lg" >{l s='Open your account' mod='expressmailing'}</a>
						<div class="em-padding-1em">
							<span class="text-primary text-left em-inline-block text em-fontsize-15px">
								<span>{l s='And receive :' mod='expressmailing'}</span><br/>
								+ <b>{l s='2500 free Email' mod='expressmailing'}</b><br/>
								+ <b>{l s='5 free Sms' mod='expressmailing'}</b><br/>
								+ <b>{l s='30 free Fax' mod='expressmailing'}</b>
							</span>
						</div>
					</div>
				</div>
				<div class="row intro-left-blocs">
					<div class="col-lg-12 text-center">
						<h2 class="text-uppercase em-nomargin-top">{l s='Our benefits' mod='expressmailing'}</h2>
						<div class="text-left em-inline-block">
							<ul class="list-unstyled">
								<li><i class="icon process-icon-ok em-config-green-icospace"></i>{l s='100% integrated with PrestaShop' mod='expressmailing'}</li>
								<li><i class="icon process-icon-ok em-config-green-icospace"></i>{l s='Free account without subscription' mod='expressmailing'}</li>
								<li><i class="icon process-icon-ok em-config-green-icospace"></i>{l s='Sending Email, Fax and Sms campaigns' mod='expressmailing'}</li>
								<li><i class="icon process-icon-ok em-config-green-icospace"></i>{l s='Sending mailing through our servers' mod='expressmailing'}</li>
								<li><i class="icon process-icon-ok em-config-green-icospace"></i>{l s='Deliverability expertise included' mod='expressmailing'}</li>
								<li><i class="icon process-icon-ok em-config-green-icospace"></i>{l s='Management of unsubscribers' mod='expressmailing'}</li>
								<li><i class="icon process-icon-ok em-config-green-icospace"></i>{l s='Management of soft and hard bounces' mod='expressmailing'}</li>
								<li><i class="icon process-icon-ok em-config-green-icospace"></i>{l s='Integrated purchases with Paybox or Paypal' mod='expressmailing'}</li>
								<li><i class="icon process-icon-ok em-config-green-icospace"></i>{l s='Immediate or delayed sending' mod='expressmailing'}</li>
								<li><i class="icon process-icon-ok em-config-green-icospace"></i>{l s='Sending segmentation over several days' mod='expressmailing'}</li>
								<li><i class="icon process-icon-ok em-config-green-icospace"></i>{l s='Sending history and statistics' mod='expressmailing'}</li>
								<li><i class="icon process-icon-ok em-config-green-icospace"></i>{l s='Fine recipient filters' mod='expressmailing'}</li>
								<li><i class="icon process-icon-ok em-config-green-icospace"></i>{l s='Tests before sending' mod='expressmailing'}</li>
								<li><i class="icon process-icon-ok em-config-green-icospace"></i>{l s='Advice and phone support included' mod='expressmailing'}</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>